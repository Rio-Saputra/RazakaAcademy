<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$filePath = storage_path('app/TRYOUT_CPNS_FORMAT_RAPI.pdf');
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($filePath);
$text   = cleanPdfText($pdf->getText());

function cleanPdfText($text) {
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $lines = explode("\n", $text);
    
    $cleanedLines = [];
    $currentLine = '';
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '') {
            if ($currentLine !== '') {
                $cleanedLines[] = $currentLine;
                $currentLine = '';
            }
            $cleanedLines[] = '';
            continue;
        }
        
        $isSpecial = false;
        $allowMerge = true;
        
        if (strpos($trimmed, '|') !== false || preg_match('/\s{3,}/', $trimmed)) {
            $isSpecial = true;
            $allowMerge = false;
        }
        elseif (preg_match('/^[\-\_=\s\+\*\/]{3,}$/', $trimmed)) {
            $isSpecial = true;
            $allowMerge = false;
        }
        elseif (preg_match('/^\s*(?:\[\d+\]|\d+[.)\:\-\] ]+|[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•])\s+/u', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        elseif (preg_match('/^\s*[A-J](?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+/i', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        elseif (preg_match('/^\s*(?:Kunci|Jawaban|Pembahasan|Penjelasan|Diskusi|Kumci)\b/i', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        
        if ($isSpecial) {
            if ($currentLine !== '') {
                $cleanedLines[] = $currentLine;
                $currentLine = '';
            }
            if ($allowMerge) {
                $currentLine = $line;
            } else {
                $cleanedLines[] = $line;
            }
        } else {
            if ($currentLine === '') {
                $currentLine = $line;
            } else {
                if (substr($currentLine, -1) === '-') {
                    $currentLine = rtrim($currentLine, '-') . $trimmed;
                } else {
                    $currentLine .= ' ' . $trimmed;
                }
            }
        }
    }
    if ($currentLine !== '') {
        $cleanedLines[] = $currentLine;
    }
    return implode("\n", $cleanedLines);
}

// Split blocks
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

// Merge blocks logic (False-split guard)
$mergedBlocks = [];
$lastQNum     = 0;
foreach ($blocks as $block) {
    $block = trim($block);
    if (empty($block)) continue;

    if (preg_match('/^\[\s*(\d+)\s*\]/u', $block, $m)) {
        $qNum = (int) $m[1];
        if ($qNum > $lastQNum) {
            $mergedBlocks[] = $block;
            $lastQNum       = $qNum;
        } else {
            if (!empty($mergedBlocks)) {
                $mergedBlocks[count($mergedBlocks) - 1] .= "\n" . $block;
            } else {
                $mergedBlocks[] = $block;
            }
        }
    } else {
        if (!empty($mergedBlocks)) {
            $mergedBlocks[count($mergedBlocks) - 1] .= "\n" . $block;
        }
    }
}
$blocks = $mergedBlocks;

echo "Total merged blocks (questions): " . count($blocks) . "\n";
foreach($blocks as $idx => $block) {
    $blockTrimmed = trim($block);
    if (empty($blockTrimmed) || !preg_match('/^\[\s*\d+\s*\]/u', $blockTrimmed)) {
        continue;
    }
    
    // Remove the [N] prefix
    $blockClean = preg_replace('/^\[\s*\d+\s*\][.)\:\-\s]*/u', '', $blockTrimmed);

    // Extract Pembahasan
    $pembahasan = '';
    if (preg_match('/(?:Pembahasan|Penjelasan|Diskusi)\s*[:\-]?\s*(.*)$/si', $blockClean, $pembMatches)) {
        $pembahasan = trim($pembMatches[1]);
    }
    
    // Clean preview of the question
    $questionText = '';
    $pa = '(?:(?:^|[\r\n])\s*A(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\bA(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
    if (preg_match('/^(.*?)(?=' . $pa . ')/sui', $blockClean, $m)) {
        $questionText = trim($m[1]);
    } else {
        $questionText = trim($blockClean);
    }
    
    echo "[$idx] Question snippet: " . substr(strip_tags($questionText), 0, 70) . "...\n";
    echo "    Has Pembahasan: " . (empty($pembahasan) ? "NO" : "YES") . "\n";
    if (!empty($pembahasan)) {
        echo "    Pembahasan snippet: " . substr(strip_tags($pembahasan), 0, 100) . "...\n";
    }
    echo "--------------------------------------------------\n";
}
