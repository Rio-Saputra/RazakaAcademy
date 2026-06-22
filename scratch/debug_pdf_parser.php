<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Smalot\PdfParser\Parser;

function cleanPdfText($text) {
    // Exact copy of cleanPdfText from controller
    $lines = explode("\n", $text);
    $cleanedLines = [];
    foreach ($lines as $line) {
        $currentLine = trim($line);
        if ($currentLine === '') continue;
        
        // Skip page headers/footers
        if (preg_match('/^(HALAMAN|PAGE|TRYOUT|RAZAKA ACADEMY)/i', $currentLine)) continue;
        if (preg_match('/^\d+\s*$/', $currentLine)) continue;
        
        $cleanedLines[] = $currentLine;
    }
    return implode("\n", $cleanedLines);
}

try {
    $filePath = __DIR__ . '/../storage/app/Soal_try_out.pdf';
    if (!file_exists($filePath)) {
        throw new \Exception("File not found at: $filePath");
    }

    echo "Loading PDF...\n";
    $parser = new Parser();
    $pdf = $parser->parseFile($filePath);
    $text = $pdf->getText();

    echo "Cleaning text...\n";
    $text = cleanPdfText($text);

    echo "Splitting blocks...\n";
    $blocks = [];
    preg_match_all('/(?:^|[\s\n\xA0\x{00a0}\x{2007}\x{202f}])(\[\s*\d+\s*\])/mu', $text, $anchorMatches, PREG_OFFSET_CAPTURE);

    if (!empty($anchorMatches[1])) {
        $anchors = $anchorMatches[1];
        foreach ($anchors as $idx => $anchor) {
            $start = $anchor[1];
            if (isset($anchors[$idx + 1])) {
                $end = $anchors[$idx + 1][1];
                while ($end > $start && in_array($text[$end - 1], [' ', "\n", "\t", "\r"])) {
                    $end--;
                }
                $block = substr($text, $start, $end - $start);
            } else {
                $block = substr($text, $start);
            }
            $block = trim($block);
            if (!empty($block)) {
                $blocks[] = $block;
            }
        }
    }

    echo "Total blocks found: " . count($blocks) . "\n\n";

    // Let's find the target block
    $targetBlock = null;
    $targetIdx = -1;
    foreach ($blocks as $idx => $block) {
        if (stripos($block, 'Bacalah pernyataan') !== false) {
            $targetBlock = $block;
            $targetIdx = $idx;
            break;
        }
    }

    if ($targetBlock === null) {
        echo "Target block not found. Showing first 3 blocks instead:\n";
        for ($i = 0; $i < min(3, count($blocks)); $i++) {
            echo "--- BLOCK $i ---\n" . $blocks[$i] . "\n\n";
        }
    } else {
        echo "=== TARGET BLOCK [Index $targetIdx] ===\n";
        echo $targetBlock . "\n\n";

        // Let's run the parsing algorithm
        $blockTrimmed = trim($targetBlock);
        $blockClean = preg_replace('/^\[\s*\d+\s*\][.)\:\-\s]*/u', '', $blockTrimmed);

        $pembahasan = '';
        if (preg_match('/(?:Pembahasan|Penjelasan|Diskusi)\s*[:\-]?\s*(.*)$/si', $blockClean, $pembMatches)) {
            $pembahasan = trim($pembMatches[1]);
            $blockClean = preg_replace('/(?:Pembahasan|Penjelasan|Diskusi)\s*[:\-]?\s*(.*)$/si', '', $blockClean);
            echo "DEBUG: Extracted Pembahasan: " . substr($pembahasan, 0, 100) . "...\n";
        }

        $la = 'A'; $lb = 'B'; $lc = 'C'; $ld = 'D'; $le = 'E';
        $pa  = '(?:(?:^|[\r\n])\s*' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
        $pb  = '(?:(?:^|[\r\n])\s*' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
        $pc  = '(?:(?:^|[\r\n])\s*' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
        $pd  = '(?:(?:^|[\r\n])\s*' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
        $pe  = '(?:(?:^|[\r\n])\s*' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
        $pk  = '\b(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-\.]?\s*';

        $questionText = '';
        if (preg_match('/^(.*?)(?=' . $pa . ')/sui', $blockClean, $m)) {
            $questionText = trim($m[1]);
        } else {
            $questionText = trim($blockClean);
        }

        // Check Option A
        $optionA = '';
        if (preg_match('/' . $pa . '(.*?)(?=' . $pb . ')/sui', $blockClean, $m)) {
            $optionA = trim($m[1]);
        }

        // Check Option B
        $optionB = '';
        if (preg_match('/' . $pb . '(.*?)(?=' . $pc . ')/sui', $blockClean, $m)) {
            $optionB = trim($m[1]);
        }

        // Check Option C
        $optionC = '';
        if (preg_match('/' . $pc . '(.*?)(?=' . $pd . ')/sui', $blockClean, $m)) {
            $optionC = trim($m[1]);
        }

        // Check Option D & E
        $optionD = ''; $optionE = '';
        $hasE = (bool) preg_match('/' . $pe . '/ui', $blockClean);
        echo "DEBUG: Has Option E: " . ($hasE ? 'YES' : 'NO') . "\n";

        if ($hasE) {
            if (preg_match('/' . $pd . '(.*?)(?=' . $pe . ')/sui', $blockClean, $m)) {
                $optionD = trim($m[1]);
            }
            if (preg_match('/' . $pe . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                $optionE = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-\.]?\s*[A-Z]/i', '', $m[1]));
            }
        } else {
            if (preg_match('/' . $pd . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                $optionD = trim($m[1]);
            }
        }

        // --- Clean options from bullet lists or passages that should belong to the question text ---
        $optionsToClean = [
            'opsi_a' => &$optionA,
            'opsi_b' => &$optionB,
            'opsi_c' => &$optionC,
            'opsi_d' => &$optionD,
            'opsi_e' => &$optionE
        ];
        $extractedBullets = [];

        foreach ($optionsToClean as $key => &$optVal) {
            if (empty($optVal)) continue;

            $lines = explode("\n", $optVal);
            $newOptLines = [];
            $bulletLines = [];

            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                if (preg_match('/^[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•]/u', $trimmedLine) || 
                    preg_match('/^\d+[.)\:\-\s]+/u', $trimmedLine) || 
                    preg_match('/^\[\s*\d+\s*\]/u', $trimmedLine) || 
                    preg_match('/^\(\s*\d+\s*\)/u', $trimmedLine) ||
                    (!empty($bulletLines) && !preg_match('/^[A-Z][.)\:\-]/i', $trimmedLine))) {
                    $bulletLines[] = $line;
                } else {
                    $newOptLines[] = $line;
                }
            }

            if (!empty($bulletLines)) {
                $optVal = trim(implode("\n", $newOptLines));
                $extractedBullets[] = implode("\n", $bulletLines);
            }
        }

        if (!empty($extractedBullets)) {
            $questionText .= "\n\n" . implode("\n", $extractedBullets);
        }

        echo "=== PARSED QUESTION TEXT ===\n";
        echo $questionText . "\n\n";
        echo "=== PARSED OPTION A ===\n$optionA\n\n";
        echo "=== PARSED OPTION B ===\n$optionB\n\n";
        echo "=== PARSED OPTION C ===\n$optionC\n\n";
        echo "=== PARSED OPTION D ===\n$optionD\n\n";
        echo "=== PARSED OPTION E ===\n$optionE\n\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
