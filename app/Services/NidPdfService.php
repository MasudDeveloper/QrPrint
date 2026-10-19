<?php

namespace App\Services;

use FPDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NidPdfService
{
    /**
     * Standard ISO/IEC 7810 ID-1 card dimensions in millimeters:
     * 85.60 mm width x 53.98 mm height (approx 85.6mm x 54.0mm)
     */
    const CARD_WIDTH_MM = 85.6;
    const CARD_HEIGHT_MM = 54.0;
    const A4_WIDTH_MM = 210.0;
    const A4_HEIGHT_MM = 297.0;

    /**
     * Generate standard A4 PDF containing front and back NID cards.
     *
     * @param string $frontImagePath Path to front card image (local filesystem)
     * @param string $backImagePath  Path to back card image (local filesystem)
     * @param string $colorMode      'bw' or 'color'
     * @param string $outputPdfPath  Destination PDF path
     * @return string Output PDF path
     */
    public function generateA4NidPdf(string $frontImagePath, string $backImagePath, string $colorMode, string $outputPdfPath): string
    {
        $processedFront = $this->prepareCardImage($frontImagePath, $colorMode, 'front');
        $processedBack  = $this->prepareCardImage($backImagePath, $colorMode, 'back');

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->AddPage();

        // Exact horizontal centering on A4 (210mm wide)
        $centerX = (self::A4_WIDTH_MM - self::CARD_WIDTH_MM) / 2; // 62.2 mm

        // Standard Bangladesh photocopy shop vertical layout:
        // Top front card at Y = 40 mm, gap 15 mm, bottom back card at Y = 109 mm
        $yFront = 40.0;
        $yBack = $yFront + self::CARD_HEIGHT_MM + 15.0; // 109.0 mm

        // Light gray cutting guidelines around the cards (0.15mm border)
        $pdf->SetDrawColor(210, 210, 210);
        $pdf->SetLineWidth(0.2);
        
        // Front Card box guideline
        $pdf->Rect($centerX - 0.4, $yFront - 0.4, self::CARD_WIDTH_MM + 0.8, self::CARD_HEIGHT_MM + 0.8);
        $pdf->Image($processedFront, $centerX, $yFront, self::CARD_WIDTH_MM, self::CARD_HEIGHT_MM);

        // Back Card box guideline
        $pdf->Rect($centerX - 0.4, $yBack - 0.4, self::CARD_WIDTH_MM + 0.8, self::CARD_HEIGHT_MM + 0.8);
        $pdf->Image($processedBack, $centerX, $yBack, self::CARD_WIDTH_MM, self::CARD_HEIGHT_MM);

        // Optional tiny bottom footer note
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetTextColor(180, 180, 180);
        $pdf->SetXY(0, 285);
        $pdf->Cell(self::A4_WIDTH_MM, 5, 'PrintSheba Smart NID Print Service - Exact 1:1 Scale (85.6mm x 54mm)', 0, 0, 'C');

        $pdf->Output('F', $outputPdfPath);

        // Clean up temporary preprocessed files if created
        if ($processedFront !== $frontImagePath && file_exists($processedFront)) {
            @unlink($processedFront);
        }
        if ($processedBack !== $backImagePath && file_exists($processedBack)) {
            @unlink($processedBack);
        }

        return $outputPdfPath;
    }

    /**
     * Preprocess image: convert to high-contrast grayscale if B&W, optimize dimensions.
     */
    protected function prepareCardImage(string $srcPath, string $colorMode, string $tag): string
    {
        if (!file_exists($srcPath)) {
            throw new \Exception("Card image file not found: {$srcPath}");
        }

        $imgInfo = @getimagesize($srcPath);
        if (!$imgInfo) {
            throw new \Exception("Invalid image file format: {$srcPath}");
        }

        $mime = $imgInfo['mime'];
        $srcImage = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($srcPath),
            'image/png'               => @imagecreatefrompng($srcPath),
            'image/webp'              => @imagecreatefromwebp($srcPath),
            default                   => null,
        };

        if (!$srcImage) {
            return $srcPath;
        }

        $origW = imagesx($srcImage);
        $origH = imagesy($srcImage);

        // Ensure target resolution: 1012 x 638 (approx 300 DPI for standard ID-1 card)
        $targetW = 1012;
        $targetH = 638;

        $canvas = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        // Fill background white
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, $white);

        imagecopyresampled($canvas, $srcImage, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);
        imagedestroy($srcImage);

        if ($colorMode === 'bw') {
            // Apply high-quality grayscale + contrast boost for laser printing
            imagefilter($canvas, IMG_FILTER_GRAYSCALE);
            imagefilter($canvas, IMG_FILTER_CONTRAST, -10); // slight contrast boost
        }

        $tempPath = storage_path('app/temp_nid_' . $tag . '_' . Str::random(10) . '.jpg');
        imagejpeg($canvas, $tempPath, 95);
        imagedestroy($canvas);

        return $tempPath;
    }
}
