<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

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
        elseif (preg_match('/^\s*(?:\[\d+\]|\d+[.)\:\-\]]+|[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•])\s+/u', $trimmed)) {
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

$pdfPath = __DIR__.'/../storage/app/TRYOUT_CPNS_FORMAT_RAPI.pdf';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($pdfPath);
$text   = $pdf->getText();

$text = cleanPdfText($text);

// Block splitting
$blocks = [];
preg_match_all('/(?:^|[\s\n])(\[\d+\])/m', $text, $anchorMatches, PREG_OFFSET_CAPTURE);

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

// Merge blocks (False-split guard)
$mergedBlocks = [];
$lastQNum     = 0;
foreach ($blocks as $block) {
    $block = trim($block);
    if (empty($block)) continue;

    if (preg_match('/^\[(\d+)\]/', $block, $m)) {
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

echo "Total Blocks parsed: " . count($blocks) . "\n\n";

// Display first 5 blocks and some math blocks (e.g. 38, 39, 40)
foreach ($blocks as $block) {
    if (preg_match('/^\[(\d+)\]/', $block, $m)) {
        $qNum = (int)$m[1];
        if ($qNum <= 5 || ($qNum >= 37 && $qNum <= 41) || $qNum >= 95) {
            echo "================ QUESTION $qNum ================\n";
            
            $blockClean = preg_replace('/^\[\d+\]\s*/', '', $block);
            
            // Extract pembahasan
            $pembahasan = '';
            if (preg_match('/(?:Pembahasan|Penjelasan|Diskusi):\s*(.*)$/si', $blockClean, $pembMatches)) {
                $pembahasan = trim($pembMatches[1]);
                $blockClean = preg_replace('/(?:Pembahasan|Penjelasan|Diskusi):\s*(.*)$/si', '', $blockClean);
            }
            
            $la = 'A'; $lb = 'B'; $lc = 'C'; $ld = 'D'; $le = 'E';
            if (preg_match('/\bF[.)\:\-]?[\s\xA0]+/u', $blockClean) &&
                preg_match('/\bG[.)\:\-]?[\s\xA0]+/u', $blockClean)) {
                $la = 'F'; $lb = 'G'; $lc = 'H'; $ld = 'I'; $le = 'J';
            }
            
            $pa = '(?:(?:^|[\r\n])\s*' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
            $pb = '(?:(?:^|[\r\n])\s*' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
            $pc = '(?:(?:^|[\r\n])\s*' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
            $pd = '(?:(?:^|[\r\n])\s*' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
            $pe = '(?:(?:^|[\r\n])\s*' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
            $pk = '\b(?:Kunci|Jawaban|Kunci Jawaban):\s*';
            
            $questionText = '';
            $optionA = ''; $optionB = ''; $optionC = ''; $optionD = ''; $optionE = null;
            
            if (preg_match('/^(.*?)(?=' . $pa . ')/sui', $blockClean, $m)) {
                $questionText = trim($m[1]);
            } else {
                $questionText = trim($blockClean);
            }
            
            if (preg_match('/' . $pa . '(.*?)(?=' . $pb . ')/sui', $blockClean, $m)) {
                $optionA = trim($m[1]);
            }
            if (preg_match('/' . $pb . '(.*?)(?=' . $pc . ')/sui', $blockClean, $m)) {
                $optionB = trim($m[1]);
            }
            if (preg_match('/' . $pc . '(.*?)(?=' . $pd . ')/sui', $blockClean, $m)) {
                $optionC = trim($m[1]);
            }
            
            $hasE = (bool) preg_match('/' . $pe . '/ui', $blockClean);
            if ($hasE) {
                if (preg_match('/' . $pd . '(.*?)(?=' . $pe . ')/sui', $blockClean, $m)) {
                    $optionD = trim($m[1]);
                }
                if (preg_match('/' . $pe . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                    $optionE = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban):\s*[A-Z]/i', '', $m[1]));
                }
            } else {
                if (preg_match('/' . $pd . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                    $optionD = trim($m[1]);
                }
            }
            
            $optionD = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban):\s*[A-Z]/i', '', $optionD));
            
            // Clean placeholders
            if (empty($questionText)) {
                $questionText = '[Soal Bergambar – Silakan merujuk pada PDF]';
            }
            if (empty($optionA)) $optionA = '[Gambar ' . $la . ']';
            if (empty($optionB)) $optionB = '[Gambar ' . $lb . ']';
            if (empty($optionC)) $optionC = '[Gambar ' . $lc . ']';
            if (empty($optionD)) $optionD = '[Gambar ' . $ld . ']';
            if ($hasE && empty($optionE)) $optionE = '[Gambar ' . $le . ']';
            
            // Detect key
            $correctAnswer = 'a';
            if (preg_match('/(?:Kunci|Jawaban|Kunci Jawaban|Kumci)\s*[:\-\.]?\s*([A-Z])\b/i', $block, $keyMatch)) {
                $correctAnswer = strtolower($keyMatch[1]);
            } elseif (!empty($pembahasan)) {
                if (preg_match('/(?:kunci|jawaban|jawaban yang benar adalah|kunci jawaban adalah|maka jawaban|yaitu|adalah)\s*([A-Z])\b/i', $pembahasan, $keyMatch)) {
                    $correctAnswer = strtolower($keyMatch[1]);
                }
            }
            
            echo "TEXT: $questionText\n";
            echo "A: $optionA\n";
            echo "B: $optionB\n";
            echo "C: $optionC\n";
            echo "D: $optionD\n";
            if ($hasE) echo "E: $optionE\n";
            echo "KEY: $correctAnswer\n";
            echo "PEMBAHASAN: " . substr($pembahasan, 0, 100) . "...\n\n";
        }
    }
}
