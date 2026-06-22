<?php
$text = file_get_contents(__DIR__.'/extracted_text.txt');
$lines = explode("\n", $text);

// Search for "[40]"
foreach ($lines as $i => $line) {
    if (strpos($line, '[40]') !== false) {
        // print 30 lines after
        for ($j = $i; $j <= min(count($lines) - 1, $i + 30); $j++) {
            echo "Line " . ($j + 1) . ": '" . $lines[$j] . "'\n";
        }
    }
}
