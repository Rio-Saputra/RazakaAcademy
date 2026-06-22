<?php
$text = file_get_contents(__DIR__.'/extracted_text.txt');
$lines = explode("\n", $text);

// Search for "[39]"
foreach ($lines as $i => $line) {
    if (strpos($line, '[39]') !== false) {
        // print 15 lines before and after
        for ($j = max(0, $i - 5); $j <= min(count($lines) - 1, $i + 15); $j++) {
            echo "Line " . ($j + 1) . ": '" . bin2hex($lines[$j]) . "' => '" . $lines[$j] . "'\n";
        }
    }
}
