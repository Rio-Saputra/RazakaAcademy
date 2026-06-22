<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdfPath = __DIR__.'/../storage/app/Soal_try_out.pdf';
if (!file_exists($pdfPath)) {
    echo "File not found: $pdfPath\n";
    exit(1);
}

$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);
$text   = $pdf->getText();

// Write the first 5000 characters of extracted text to check
file_put_contents(__DIR__.'/extracted_text_2.txt', $text);
echo "Extracted text length: " . strlen($text) . "\n";
echo "Saved to extracted_text_2.txt\n";

