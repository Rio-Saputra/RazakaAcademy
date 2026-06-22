<?php

function checkFolder($dir) {
    $errors = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isDir()) continue;
        if ($file->getExtension() === 'php') {
            $path = $file->getRealPath();
            // Run php -l command
            $output = [];
            $returnVar = 0;
            exec("php -l " . escapeshellarg($path) . " 2>&1", $output, $returnVar);
            if ($returnVar !== 0) {
                $errors[] = [
                    'file' => $path,
                    'error' => implode("\n", $output)
                ];
            }
        }
    }
    return $errors;
}

echo "Starting syntax check across folders...\n";
$folders = [
    __DIR__ . '/../app',
    __DIR__ . '/../config',
    __DIR__ . '/../routes',
    __DIR__ . '/../database/migrations'
];

$allErrors = [];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        echo "Checking $folder...\n";
        $errors = checkFolder($folder);
        $allErrors = array_merge($allErrors, $errors);
    } else {
        echo "Skipping non-existent folder: $folder\n";
    }
}

echo "\n--- SYNTAX CHECK RESULT ---\n";
if (empty($allErrors)) {
    echo "SUCCESS: No syntax errors found in any PHP files!\n";
} else {
    echo "FAILED: Found " . count($allErrors) . " syntax error(s):\n";
    foreach ($allErrors as $err) {
        echo "- File: {$err['file']}\n";
        echo "  Error:\n{$err['error']}\n\n";
    }
}
