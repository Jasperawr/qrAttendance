<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

require 'vendor/autoload.php';


function qrGenerate($data)
{
    $qr_code = QrCode::create($data)
        ->setSize(300) // Smaller size for faster generation
        ->setMargin(10) // Reduce margin
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low); // Lower error correction level

    $writer = new SvgWriter; // SVG is faster than PNG
    $result = $writer->write($qr_code);

    // Return the QR code as a base64 string
    return $result->getDataUri();
}
