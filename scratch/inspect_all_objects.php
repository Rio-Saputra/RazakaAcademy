<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdfPath = __DIR__.'/../storage/app/TRYOUT_CPNS_FORMAT_RAPI.pdf';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);

$xobjects = $pdf->getObjects();
echo "Total Objects: " . count($xobjects) . "\n";

$types = [];
foreach ($xobjects as $id => $obj) {
    $type = get_class($obj);
    if (!isset($types[$type])) {
        $types[$type] = 0;
    }
    $types[$type]++;
    
    // Check if it's an image
    if ($obj instanceof \Smalot\PdfParser\XObject\Image) {
        echo "Image object found: $id\n";
    }
}

print_r($types);
