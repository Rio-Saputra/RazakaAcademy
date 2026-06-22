<?php

function cleanPdfText($text) {
    // Normalize line endings
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
            $cleanedLines[] = ''; // Keep paragraph break
            continue;
        }
        
        // Check if this line should NOT be merged with the previous one
        $isSpecial = false;
        $allowMerge = true;
        
        // 1. Table row (contains | or 3+ spaces)
        if (strpos($trimmed, '|') !== false || preg_match('/\s{3,}/', $trimmed)) {
            $isSpecial = true;
            $allowMerge = false;
        }
        // 2. Math fraction bar / line separator
        elseif (preg_match('/^[\-\_=\s\+\*\/]{3,}$/', $trimmed)) {
            $isSpecial = true;
            $allowMerge = false;
        }
        // 3. Starts with list/question marker, e.g. [1], 1., or bullets
        elseif (preg_match('/^\s*(?:\[\d+\]|\d+[.)\:\-\]]+|[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•])\s+/u', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        // 4. Starts with option marker like A. B. C. etc. or A (with space/punctuation)
        elseif (preg_match('/^\s*[A-J](?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+/i', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        // 5. Starts with keyword
        elseif (preg_match('/^\s*(?:Kunci|Jawaban|Pembahasan|Penjelasan|Diskusi|Kumci)\b/i', $trimmed)) {
            $isSpecial = true;
            $allowMerge = true;
        }
        
        if ($isSpecial) {
            // Push current accumulated line first
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
            // Merge with current line
            if ($currentLine === '') {
                $currentLine = $line;
            } else {
                // If previous line ends with a hyphen, merge cleanly (e.g. "menur- unkan" -> "menurunkan")
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

$text = file_get_contents(__DIR__.'/extracted_text.txt');
$cleaned = cleanPdfText($text);
file_put_contents(__DIR__.'/cleaned_text.txt', $cleaned);
echo "Cleaned text saved to cleaned_text.txt\n";
