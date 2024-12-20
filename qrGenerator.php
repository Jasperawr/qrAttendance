<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require 'vendor/autoload.php';

function qrGenerate($data)
{
    $qr_code = QrCode::create($data)
        ->setSize(600)
        ->setMargin(40);

    $writer = new PngWriter;
    $result = $writer->write($qr_code);

    // Return the QR code as a base64 string
    return $result->getDataUri();
}
