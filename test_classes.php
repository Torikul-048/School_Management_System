<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CLASSES & SECTIONS TEST ===\n\n";

echo "Classes:\n";
$classes = App\Models\Classes::orderBy('name')->get();
foreach ($classes as $class) {
    echo "  - ID {$class->id}: {$class->name}\n";
}

echo "\nSections:\n";
$sections = App\Models\Section::orderBy('name')->get();
foreach ($sections as $section) {
    echo "  - ID {$section->id}: {$section->name}\n";
}

echo "\n=== TEST COMPLETE ===\n";
