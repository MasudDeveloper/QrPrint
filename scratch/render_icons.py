import os
import subprocess
from PIL import Image

scratch_dir = r"d:\MY Project\QrPrint\scratch"
public_dir = r"d:\MY Project\QrPrint\public"
logo_svg = os.path.join(public_dir, "logo.svg")
temp_html = os.path.join(scratch_dir, "icon_render.html")
screenshot_png = os.path.join(scratch_dir, "rendered_512.png")
edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

html_content = f"""<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * {{ margin: 0; padding: 0; box-sizing: border-box; }}
  html, body {{
    width: 512px;
    height: 512px;
    overflow: hidden;
    background: transparent;
  }}
  img {{
    width: 512px;
    height: 512px;
    display: block;
  }}
</style>
</head>
<body>
<img src="file:///{logo_svg.replace(os.sep, '/')}" />
</body>
</html>"""

with open(temp_html, "w", encoding="utf-8") as f:
    f.write(html_content)

cmd = [
    edge_path,
    "--headless",
    "--disable-gpu",
    "--hide-scrollbars",
    f"--screenshot={screenshot_png}",
    "--window-size=512,512",
    "--default-background-color=00000000",
    f"file:///{temp_html.replace(os.sep, '/')}"
]

print("Running Edge screenshot...")
result = subprocess.run(cmd, capture_output=True, text=True)
print("Return code:", result.returncode)

if os.path.exists(screenshot_png):
    img = Image.open(screenshot_png).convert("RGBA")
    print(f"Captured image size: {img.size}")
    
    # 1. 512x512 standard
    img_512 = img.resize((512, 512), Image.Resampling.LANCZOS)
    img_512.save(os.path.join(public_dir, "icon-512.png"), "PNG")
    print("Saved icon-512.png")
    
    # 2. 192x192 standard
    img_192 = img.resize((192, 192), Image.Resampling.LANCZOS)
    img_192.save(os.path.join(public_dir, "icon-192.png"), "PNG")
    print("Saved icon-192.png")
    
    # 3. Apple touch icon 180x180
    img_180 = img.resize((180, 180), Image.Resampling.LANCZOS)
    img_180.save(os.path.join(public_dir, "apple-touch-icon.png"), "PNG")
    print("Saved apple-touch-icon.png")

    # 4. Favicon 32x32 and 16x16
    img_32 = img.resize((32, 32), Image.Resampling.LANCZOS)
    img_32.save(os.path.join(public_dir, "favicon-32x32.png"), "PNG")
    img_16 = img.resize((16, 16), Image.Resampling.LANCZOS)
    img_16.save(os.path.join(public_dir, "favicon-16x16.png"), "PNG")

    # 5. Multi-size favicon.ico
    img_512.save(os.path.join(public_dir, "favicon.ico"), format="ICO", sizes=[(16, 16), (32, 32), (48, 48), (64, 64)])
    print("Saved favicon.ico")

    # 6. Maskable icons with safe zone margins (Android PWA adaptive icons)
    # Android maskable safe zone is inner 80% circle
    maskable_bg = Image.new("RGBA", (512, 512), (15, 23, 42, 255)) # #0F172A
    scaled_inner = img.resize((410, 410), Image.Resampling.LANCZOS)
    maskable_bg.paste(scaled_inner, (51, 51), scaled_inner)
    maskable_bg.save(os.path.join(public_dir, "icon-maskable-512.png"), "PNG")
    
    maskable_192 = maskable_bg.resize((192, 192), Image.Resampling.LANCZOS)
    maskable_192.save(os.path.join(public_dir, "icon-maskable-192.png"), "PNG")
    print("Saved maskable icons")
    print("ALL ICONS GENERATED SUCCESSFULLY!")
else:
    print("Screenshot failed to generate!")
