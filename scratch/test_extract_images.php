<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdfPath = __DIR__.'/../storage/app/Soal_try_out.pdf';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);

$xobjects = $pdf->getObjectsByType('XObject');
echo "Total XObjects: " . count($xobjects) . "\n";

$imageCount = 0;
foreach ($xobjects as $id => $xobject) {
    if ($xobject instanceof \Smalot\PdfParser\XObject\Image) {
        $imageCount++;
        echo "Found image with ID: $id\n";
    }
}
echo "Total images found: $imageCount\n";
