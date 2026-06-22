<?php
$text = file_get_contents(__DIR__.'/extracted_text.txt');
$lines = explode("\n", $text);
$total = count($lines);
for ($i = max(0, $total - 150); $i < $total; $i++) {
    echo "Line " . ($i + 1) . ": " . $lines[$i] . "\n";
}
