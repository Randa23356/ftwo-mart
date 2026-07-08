<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class TestImageController extends Controller
{
    public function testImages()
    {
        echo "<h1>Test Images Accessibility</h1>";
        
        // Test website settings images
        $imageKeys = ['logo', 'hero_image', 'about_image'];
        
        echo "<h2>Website Settings Images</h2>";
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Key</th><th>Path in DB</th><th>Storage Exists</th><th>ImageHelper URL</th><th>Direct URL</th><th>Preview</th></tr>";
        
        foreach ($imageKeys as $key) {
            $value = WebsiteSetting::getValue($key);
            $exists = $value ? Storage::disk('public')->exists($value) : false;
            $imageUrl = $value ? ImageHelper::getImageUrl($value) : null;
            $directUrl = $value ? asset('storage/' . $value) : null;
            
            echo "<tr>";
            echo "<td>$key</td>";
            echo "<td>" . ($value ?: 'NULL') . "</td>";
            echo "<td>" . ($exists ? '✓' : '✗') . "</td>";
            echo "<td>" . ($imageUrl ? "<a href='$imageUrl'>$imageUrl</a>" : 'N/A') . "</td>";
            echo "<td>" . ($directUrl ? "<a href='$directUrl'>$directUrl</a>" : 'N/A') . "</td>";
            
            if ($imageUrl) {
                echo "<td><img src='$imageUrl' style='max-width: 100px; max-height: 100px;'></td>";
            } else {
                echo "<td>No image</td>";
            }
            
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Test all files in website folder
        echo "<h2>All Files in website/ Folder</h2>";
        $files = Storage::disk('public')->files('website');
        
        if (count($files) > 0) {
            echo "<table border='1' cellpadding='8'>";
            echo "<tr><th>File</th><th>Size</th><th>URL</th><th>Preview</th></tr>";
            
            foreach ($files as $file) {
                $size = Storage::disk('public')->size($file);
                $url = ImageHelper::getImageUrl($file);
                
                echo "<tr>";
                echo "<td>$file</td>";
                echo "<td>" . number_format($size) . " bytes</td>";
                echo "<td><a href='$url'>$url</a></td>";
                
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    echo "<td><img src='$url' style='max-width: 100px; max-height: 100px;'></td>";
                } else {
                    echo "<td>Not an image</td>";
                }
                
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p>No files found in website folder.</p>";
        }
        
        // Test storage link
        echo "<h2>Storage Link Test</h2>";
        $storageLink = public_path('storage');
        $isLink = is_link($storageLink);
        
        echo "<p>public/storage is link: " . ($isLink ? '✓ Yes' : '✗ No') . "</p>";
        
        if ($isLink) {
            $target = readlink($storageLink);
            echo "<p>Link target: $target</p>";
            echo "<p>Target exists: " . (file_exists($target) ? '✓ Yes' : '✗ No') . "</p>";
        }
        
        // Recommendations
        echo "<h2>Recommendations</h2>";
        echo "<ul>";
        
        if (!$isLink) {
            echo "<li>❌ Storage link tidak ada. Jalankan: <code>php artisan storage:link</code></li>";
        }
        
        // Check if any image keys have no value
        $emptyKeys = [];
        foreach ($imageKeys as $key) {
            if (!WebsiteSetting::getValue($key)) {
                $emptyKeys[] = $key;
            }
        }
        
        if (count($emptyKeys) > 0) {
            echo "<li>⚠️  Image settings missing: " . implode(', ', $emptyKeys) . ". Upload images via admin panel.</li>";
        }
        
        // Check APP_URL
        $appUrl = config('app.url');
        echo "<li>APP_URL: $appUrl</li>";
        
        if (strpos($appUrl, 'localhost') !== false || strpos($appUrl, '127.0.0.1') !== false) {
            echo "<li>⚠️  APP_URL masih localhost. Di production, ubah ke domain Anda.</li>";
        }
        
        echo "</ul>";
        
        echo "<h2>Quick Fixes</h2>";
        echo "<p>1. <a href='" . url('admin/settings') . "'>Go to Admin Settings</a> to upload images</p>";
        echo "<p>2. Run cleanup: <a href='" . route('image.cleanup') . "'>Cleanup unused images</a></p>";
        
        return ''; // Already outputting HTML
    }
    
    public function cleanup()
    {
        // Simple cleanup endpoint
        $files = Storage::disk('public')->files('website');
        $imageKeys = ['logo', 'hero_image', 'about_image'];
        $usedImages = [];
        
        foreach ($imageKeys as $key) {
            $value = WebsiteSetting::getValue($key);
            if ($value) {
                $usedImages[] = $value;
            }
        }
        
        $deleted = [];
        foreach ($files as $file) {
            if (!in_array($file, $usedImages)) {
                Storage::disk('public')->delete($file);
                $deleted[] = $file;
            }
        }
        
        return response()->json([
            'message' => 'Cleanup completed',
            'deleted_count' => count($deleted),
            'deleted_files' => $deleted,
            'used_images' => $usedImages
        ]);
    }
}