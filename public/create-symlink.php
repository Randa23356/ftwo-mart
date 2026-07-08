<?php

// Diagnostic & fix script for Laravel storage symlink
// DELETE THIS FILE IMMEDIATELY AFTER RUNNING!

$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

echo "<pre>";
echo "=== Storage Diagnostic ===\n\n";

// Check if target directory exists
echo "1. Target directory (storage/app/public): ";
if (is_dir($target)) {
    echo "EXISTS\n";
} else {
    echo "MISSING\n";
    echo "   Attempting to create...\n";
    if (mkdir($target, 0775, true)) {
        echo "   Created successfully\n";
    } else {
        echo "   FAILED to create\n";
    }
}

// Check if products subdirectory exists
$productsDir = $target . '/products';
echo "2. Products directory (storage/app/public/products): ";
if (is_dir($productsDir)) {
    $files = glob($productsDir . '/*');
    echo "EXISTS (" . count($files) . " files)\n";
} else {
    echo "MISSING\n";
    if (mkdir($productsDir, 0775, true)) {
        echo "   Created successfully\n";
    } else {
        echo "   FAILED to create\n";
    }
}

// Check symlink status
echo "3. Symlink (public/storage): ";
if (is_link($link)) {
    $realPath = readlink($link);
    echo "EXISTS -> " . $realPath . "\n";
    if (!is_dir($link)) {
        echo "   WARNING: Symlink is BROKEN (target doesn't exist)!\n";
        echo "   Removing broken symlink...\n";
        unlink($link);
        echo "   Removed. Will recreate...\n";
    }
} elseif (is_dir($link)) {
    echo "EXISTS as DIRECTORY (not a symlink)\n";
} elseif (file_exists($link)) {
    echo "EXISTS as FILE (unexpected)\n";
} else {
    echo "MISSING\n";
}

// Create symlink if needed
if (!file_exists($link) && !is_link($link)) {
    echo "4. Creating symlink...\n";
    echo "   Target: " . realpath($target) . "\n";
    echo "   Link:   " . $link . "\n";

    if (function_exists('symlink')) {
        if (symlink(realpath($target), $link)) {
            echo "   SUCCESS\n";
        } else {
            echo "   FAILED\n";
            echo "   Error: " . error_get_last()['message'] . "\n";
        }
    } else {
        echo "   symlink() function is DISABLED on this server\n";
        echo "   The fallback route in routes/web.php will handle file serving.\n";
    }
}

echo "\n=== Done ===\n";
echo "DELETE THIS FILE (create-symlink.php) IMMEDIATELY for security!\n";
echo "</pre>";
