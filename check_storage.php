<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Storage Diagnostic Tool ===\n\n";

// Check if storage link exists
$storageLink = __DIR__ . '/public/storage';
if (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "✓ Storage link exists\n";
    echo "  Link: $storageLink\n";
    echo "  Target: $linkTarget\n";
    
    // Check if target exists
    if (file_exists($linkTarget)) {
        echo "  ✓ Link target exists\n";
    } else {
        echo "  ✗ Link target does not exist: $linkTarget\n";
    }
} else {
    echo "✗ Storage link does not exist or is not a symbolic link\n";
}

echo "\n=== Storage Disk Check ===\n";

// Check public disk
try {
    $files = Storage::disk('public')->files('website');
    echo "✓ Public disk accessible\n";
    echo "  Files in website folder: " . count($files) . "\n";
    
    foreach ($files as $file) {
        $exists = Storage::disk('public')->exists($file);
        $size = Storage::disk('public')->size($file);
        echo "  - $file: " . ($exists ? "Exists" : "Missing") . " ($size bytes)\n";
    }
} catch (\Exception $e) {
    echo "✗ Error accessing public disk: " . $e->getMessage() . "\n";
}

echo "\n=== URL Generation Test ===\n";

// Test URL generation
$testFiles = ['website/test.png', 'website/logo.png', 'website/hero.png'];
foreach ($testFiles as $testFile) {
    $url = Storage::url($testFile);
    $assetUrl = asset('storage/' . $testFile);
    echo "Storage::url('$testFile'): $url\n";
    echo "asset('storage/$testFile'): $assetUrl\n";
}

echo "\n=== Checking if storage folder is writable ===\n";
$storagePath = __DIR__ . '/storage/app/public';
if (is_writable($storagePath)) {
    echo "✓ Storage folder is writable: $storagePath\n";
} else {
    echo "✗ Storage folder is not writable: $storagePath\n";
}

echo "\n=== Checking if public/storage is accessible via web ===\n";
// Simulate web access path
$webPath = '/storage/website';
echo "Web path would be: $webPath\n";
echo "Full URL would be: " . url($webPath) . "\n";

// Test with a real file if exists
$files = glob(__DIR__ . '/storage/app/public/website/*');
if (count($files) > 0) {
    $firstFile = basename($files[0]);
    $relativePath = 'website/' . $firstFile;
    echo "\nTesting with file: $firstFile\n";
    echo "Relative path: $relativePath\n";
    echo "Storage::url(): " . Storage::url($relativePath) . "\n";
    echo "asset('storage/'): " . asset('storage/' . $relativePath) . "\n";
    
    // Check if file exists via direct path
    $publicPath = __DIR__ . '/public/storage/website/' . $firstFile;
    if (file_exists($publicPath)) {
        echo "✓ File exists in public/storage: $publicPath\n";
    } else {
        echo "✗ File does not exist in public/storage: $publicPath\n";
        echo "  This is the problem! The symbolic link may not be working correctly.\n";
    }
}

echo "\n=== Recommendations ===\n";
echo "1. If storage link is broken, run: php artisan storage:link\n";
echo "2. If files exist in storage but not in public/storage, the link may need to be recreated\n";
echo "3. For production, ensure the web server has read access to the storage folder\n";
echo "4. Check .env file for APP_URL - it should match your production URL\n";