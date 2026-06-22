<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdfPath = __DIR__.'/../storage/app/Soal_try_out.pdf';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);

$pages = $pdf->getPages();
$totalImages = 0;

foreach ($pages as $num => $page) {
    echo "--- Page " . ($num + 1) . " ---\n";
    $xobjects = $page->getXObjects();
    echo "XObjects on page: " . count($xobjects) . "\n";
    foreach ($xobjects as $id => $xobject) {
        if ($xobject instanceof \Smalot\PdfParser\XObject\Image) {
            $totalImages++;
            echo "Found Image: $id, type=" . $xobject->getType() . "\n";
        }
    }
}
echo "Total Images: $totalImages\n";
