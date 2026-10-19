from contextlib import asynccontextmanager
from fastapi import FastAPI, UploadFile, File, HTTPException, Depends, Header
from fastapi.responses import Response, JSONResponse
from rembg import remove, new_session
from PIL import Image
import io
import cv2
import numpy as np
import os

# Secret API Key to protect your server from unauthorized use
SECRET_API_KEY = os.getenv("PYTHON_AI_API_KEY", "penghor_secret_key_121")

# Global session variables — loaded once at startup
session = None
card_session = None

@asynccontextmanager
async def lifespan(app: FastAPI):
    """Load AI models once at startup and release on shutdown."""
    global session, card_session
    print("[Startup] Loading AI models...")

    try:
        # silueta: better than u2net_human_seg for complex outdoor backgrounds and full-body portraits
        session = new_session("silueta")
    except Exception as e:
        print(f"[Startup Warning] Could not load silueta session: {e}")

    try:
        # u2net: best general-purpose model for objects, ID cards, documents
        card_session = new_session("u2net")
    except Exception as e:
        print(f"[Startup Warning] Could not load u2net session: {e}")

    print("[Startup] Model initialization completed.")
    yield
    print("[Shutdown] Releasing resources.")

app = FastAPI(
    title="Background Removal & Document Detection API",
    description="AI-powered background removal and document detection API",
    version="2.0.0",
    lifespan=lifespan,
)

def verify_api_key(x_api_key: str = Header(None)):
    if x_api_key != SECRET_API_KEY:
        raise HTTPException(status_code=401, detail="Invalid API Key")

@app.get("/")
def read_root():
    return {"status": "running", "message": "Background Removal & Document Detection API is online", "version": "2.0.0"}

@app.get("/health")
def health_check():
    """Check if the API and models are ready."""
    models_ready = card_session is not None
    return JSONResponse(
        status_code=200 if models_ready else 503,
        content={
            "status": "healthy" if models_ready else "not_ready",
            "models_loaded": models_ready,
        }
    )

@app.get("/models")
def list_models():
    """Return info about the loaded AI models."""
    return {
        "portrait_model": "silueta",
        "object_model": "u2net",
        "info": "silueta for portraits, u2net for ID cards and documents."
    }

@app.post("/remove-bg", dependencies=[Depends(verify_api_key)])
async def remove_background(file: UploadFile = File(...)):
    """Remove background from a portrait/human image using silueta model with alpha matting."""
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=400, detail="File must be an image")

    try:
        input_data = await file.read()
        # RGB is required — silueta and u2net are both trained on RGB images
        input_image = Image.open(io.BytesIO(input_data)).convert("RGB")

        try:
            # Try with alpha matting first (requires pymatting — gives best edge quality)
            output_image = remove(
                input_image,
                session=session,
                post_process_mask=True,
                alpha_matting=True,
                alpha_matting_foreground_threshold=230,
                alpha_matting_background_threshold=20,
                alpha_matting_erode_size=5,
            )
        except Exception:
            # Fallback: alpha matting not available
            output_image = remove(
                input_image,
                session=session,
                post_process_mask=True,
            )

        img_byte_arr = io.BytesIO()
        output_image.save(img_byte_arr, format="PNG")
        output_data = img_byte_arr.getvalue()

        return Response(content=output_data, media_type="image/png")

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Processing failed: {str(e)}")


def order_points(pts):
    rect = np.zeros((4, 2), dtype="float32")
    s = pts.sum(axis=1)
    rect[0] = pts[np.argmin(s)]
    rect[2] = pts[np.argmax(s)]
    diff = np.diff(pts, axis=1)
    rect[1] = pts[np.argmin(diff)]
    rect[3] = pts[np.argmax(diff)]
    return rect

def get_document_corners_opencv(image):
    orig_h, orig_w = image.shape[:2]
    
    # Pad image so that documents touching the edge still form a closed contour
    padding = 20
    padded = cv2.copyMakeBorder(image, padding, padding, padding, padding, cv2.BORDER_CONSTANT, value=[0, 0, 0])
    
    gray = cv2.cvtColor(padded, cv2.COLOR_BGR2GRAY)
    gray = cv2.bilateralFilter(gray, 11, 17, 17)
    edged = cv2.Canny(gray, 30, 200)
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (7, 7))
    edged = cv2.morphologyEx(edged, cv2.MORPH_CLOSE, kernel)
    cnts, _ = cv2.findContours(edged.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    if not cnts:
        return None
        
    cnts = sorted(cnts, key=cv2.contourArea, reverse=True)[:5]
    for c in cnts:
        if cv2.contourArea(c) > (orig_w * orig_h) * 0.05:
            peri = cv2.arcLength(c, True)
            for eps_multiplier in np.linspace(0.01, 0.15, 50):
                approx = cv2.approxPolyDP(c, eps_multiplier * peri, True)
                if len(approx) == 4:
                    pts = approx.reshape(4, 2)
                    pts = pts - padding # Remove padding offset
                    return np.clip(pts, [0, 0], [orig_w-1, orig_h-1])
            rect = cv2.minAreaRect(c)
            pts = np.array(cv2.boxPoints(rect), dtype="float32") - padding
            return np.clip(pts, [0, 0], [orig_w-1, orig_h-1])
    
    if cnts:
        rect = cv2.minAreaRect(cnts[0])
        pts = np.array(cv2.boxPoints(rect), dtype="float32") - padding
        return np.clip(pts, [0, 0], [orig_w-1, orig_h-1])
        
    return None

def get_document_corners(image):
    try:
        # 1. Try AI mask (Great for NID cards, ignores backgrounds and inner text)
        if card_session is not None:
            input_image = Image.fromarray(cv2.cvtColor(image, cv2.COLOR_BGR2RGB))
            mask_pil = remove(input_image, session=card_session, only_mask=True)
            mask = np.array(mask_pil)
            
            _, bw = cv2.threshold(mask, 127, 255, cv2.THRESH_BINARY)
            cnts, _ = cv2.findContours(bw, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
            
            if cnts:
                c = max(cnts, key=cv2.contourArea)
                area = cv2.contourArea(c)
                image_area = image.shape[0] * image.shape[1]
                
                # Check if AI actually found a salient object and not just the whole image or nothing
                if area > image_area * 0.05 and area < image_area * 0.95:
                    peri = cv2.arcLength(c, True)
                    for eps_multiplier in np.linspace(0.01, 0.15, 50):
                        approx = cv2.approxPolyDP(c, eps_multiplier * peri, True)
                        if len(approx) == 4:
                            return approx.reshape(4, 2)
                            
                    rect = cv2.minAreaRect(c)
                    return np.array(cv2.boxPoints(rect), dtype="float32")
                
    except Exception as e:
        print("AI Detection error:", e)
        
    # 2. Fallback to robust OpenCV edge detection (Great for white A4 papers, documents)
    return get_document_corners_opencv(image)

def four_point_transform(image, pts):
    rect = order_points(pts)
    (tl, tr, br, bl) = rect
    widthA = np.sqrt(((br[0] - bl[0]) ** 2) + ((br[1] - bl[1]) ** 2))
    widthB = np.sqrt(((tr[0] - tl[0]) ** 2) + ((tr[1] - tl[1]) ** 2))
    maxWidth = max(int(widthA), int(widthB))
    heightA = np.sqrt(((tr[0] - br[0]) ** 2) + ((tr[1] - br[1]) ** 2))
    heightB = np.sqrt(((tl[0] - bl[0]) ** 2) + ((tl[1] - bl[1]) ** 2))
    maxHeight = max(int(heightA), int(heightB))
    
    # Avoid zero dimensions
    maxWidth = max(1, maxWidth)
    maxHeight = max(1, maxHeight)
    
    dst = np.array([
        [0, 0],
        [maxWidth - 1, 0],
        [maxWidth - 1, maxHeight - 1],
        [0, maxHeight - 1]], dtype="float32")
    M = cv2.getPerspectiveTransform(rect, dst)
    return cv2.warpPerspective(image, M, (maxWidth, maxHeight))

@app.post("/detect-document-corners", dependencies=[Depends(verify_api_key)])
async def detect_document_corners_api(file: UploadFile = File(...)):
    try:
        input_data = await file.read()
        nparr = np.frombuffer(input_data, np.uint8)
        image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        if image is None:
            return JSONResponse(content={"success": False, "message": "Failed to decode image"})
            
        h, w = image.shape[:2]
        corners = get_document_corners(image)
        if corners is None:
            return JSONResponse(content={"success": False, "message": "No document detected"})
            
        normalized_corners = [{"x": float(pt[0])/w, "y": float(pt[1])/h} for pt in order_points(corners)]
        return JSONResponse(content={"success": True, "corners": normalized_corners})
    except Exception as e:
        return JSONResponse(status_code=500, content={"success": False, "message": str(e)})

@app.post("/auto-crop-document", dependencies=[Depends(verify_api_key)])
async def auto_crop_document_api(file: UploadFile = File(...)):
    try:
        input_data = await file.read()
        nparr = np.frombuffer(input_data, np.uint8)
        image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        if image is None:
            raise HTTPException(status_code=400, detail="Invalid image file")
            
        corners = get_document_corners(image)
        if corners is not None:
            image = four_point_transform(image, corners)
            
        _, buffer = cv2.imencode('.jpg', image, [int(cv2.IMWRITE_JPEG_QUALITY), 95])
        return Response(content=buffer.tobytes(), media_type="image/jpeg")
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
