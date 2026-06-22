<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdfPath = __DIR__.'/../storage/app/TRYOUT_CPNS_FORMAT_RAPI.pdf';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);

$pages = $pdf->getPages();
foreach ($pages as $pageNum => $page) {
    $text = $page->getText();
    if (strpos($text, '[37]') !== false) {
        echo "Page " . ($pageNum + 1) . " contains [37]\n";
        echo "Text length: " . strlen($text) . "\n";
        echo "--- START TEXT ---\n";
        echo substr($text, 0, 1000) . "\n";
        echo "--- END TEXT ---\n";
        
        // Let's dump all resources and XObjects of this page
        $resources = $page->get('Resources');
        if ($resources instanceof \Smalot\PdfParser\PDFObject) {
            $details = $resources->getDetails();
            echo "Resources keys: " . implode(', ', array_keys($details)) . "\n";
            try {
                $xobject = $resources->has('XObject') ? $resources->get('XObject') : null;
                if ($xobject) {
                    echo "XObject details keys: " . implode(', ', array_keys($xobject->getDetails())) . "\n";
                }
            } catch (\Exception $ex) {
                echo "Error getting XObject: " . $ex->getMessage() . "\n";
            }
        }
    }
}
