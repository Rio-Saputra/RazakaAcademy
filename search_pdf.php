<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$filePath = storage_path('app/TRYOUT_CPNS_FORMAT_RAPI.pdf');
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($filePath);
$text   = $pdf->getText();

preg_match_all('/Mengenalkan\s+budaya/ui', $text, $matches, PREG_OFFSET_CAPTURE);

echo "Total occurrences of 'Mengenalkan budaya': " . count($matches[0]) . "\n";
foreach($matches[0] as $idx => $match) {
    $pos = $match[1];
    echo "--- Occurrence $idx at pos $pos ---\n";
    echo substr($text, $pos - 100, 600) . "\n\n";
}
