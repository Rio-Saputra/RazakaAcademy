<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$questions = App\Models\Question::where('jenis_soal', 'TIU')->get();
$updatedCount = 0;

foreach ($questions as $q) {
    $changed = false;

    // Fix question_text: remove both escaped and unescaped placeholder
    $origText = $q->question_text;
    
    // Escaped version
    $text = preg_replace('/&lt;div class=&quot;soal-bergambar-placeholder&quot;.*?&lt;\/div&gt;&lt;\/div&gt;/si', '', $origText);
    // Unescaped version
    $text = preg_replace('/<div class="soal-bergambar-placeholder".*?<\/div><\/div>/si', '', $text);

    if ($text !== $origText) {
        $q->question_text = trim($text);
        $changed = true;
    }

    // Fix options: remove unescaped placeholder
    foreach (['option_a', 'option_b', 'option_c', 'option_d', 'option_e'] as $opt) {
        $origOpt = $q->$opt;
        if ($origOpt) {
            $newOpt = preg_replace('/<div class="opsi-bergambar-placeholder".*?<\/div>/si', '', $origOpt);
            // Also check escaped version just in case
            $newOpt = preg_replace('/&lt;div class=&quot;opsi-bergambar-placeholder&quot;.*?&lt;\/div&gt;/si', '', $newOpt);
            
            if ($newOpt !== $origOpt) {
                $q->$opt = trim($newOpt);
                $changed = true;
            }
        }
    }

    if ($changed) {
        $q->save();
        $updatedCount++;
        echo "Cleaned Q ID: {$q->id}\n";
    }
}

echo "Total updated: $updatedCount\n";
