<?php

$blockClean = "[39] Nilai A adalah 5, nilai B adalah 10. Berapakah A + B?
A. 15
B. 20
C. 25
D. 30
E. 35
Kunci: A
Pembahasan: Bla bla";

$la = 'A'; $lb = 'B'; $lc = 'C'; $ld = 'D'; $le = 'E';

$pa = '(?:(?:^|[\r\n])\s*' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
$pb = '(?:(?:^|[\r\n])\s*' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
$pc = '(?:(?:^|[\r\n])\s*' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
$pd = '(?:(?:^|[\r\n])\s*' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
$pe = '(?:(?:^|[\r\n])\s*' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
$pk = '\b(?:Kunci|Jawaban|Kunci Jawaban):\s*';

if (preg_match('/^(.*?)(?=' . $pa . ')/sui', $blockClean, $m)) {
    echo "Question: " . trim($m[1]) . "\n\n";
} else {
    echo "No question matched\n\n";
}

if (preg_match('/' . $pa . '(.*?)(?=' . $pb . ')/sui', $blockClean, $m)) {
    echo "Option A: " . trim($m[1]) . "\n\n";
}
if (preg_match('/' . $pb . '(.*?)(?=' . $pc . ')/sui', $blockClean, $m)) {
    echo "Option B: " . trim($m[1]) . "\n\n";
}
if (preg_match('/' . $pc . '(.*?)(?=' . $pd . ')/sui', $blockClean, $m)) {
    echo "Option C: " . trim($m[1]) . "\n\n";
}
if (preg_match('/' . $pd . '(.*?)(?=' . $pe . ')/sui', $blockClean, $m)) {
    echo "Option D: " . trim($m[1]) . "\n\n";
}
if (preg_match('/' . $pe . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
    echo "Option E: " . trim($m[1]) . "\n\n";
}
