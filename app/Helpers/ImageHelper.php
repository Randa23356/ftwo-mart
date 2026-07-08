<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get URL for image stored in public storage
     * This handles both local and production environments
     */
    public static function getImageUrl($path): ?string
    {
        if (!$path) {
            return null;
        }

        // Remove 'public/' prefix if present
        $path = str_replace('public/', '', $path);
        
        // Check if file exists in storage
        if (Storage::disk('public')->exists($path)) {
            // For production safety, use asset() with storage path
            return asset('storage/' . $path);
        }
        
        return null;
    }
    
    /**
     * Delete old image if exists
     */
    public static function deleteOldImage($path): bool
    {
        if (!$path) {
            return false;
        }
        
        // Remove 'public/' prefix if present
        $path = str_replace('public/', '', $path);
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
    
    /**
     * Store uploaded image and return path
     */
    public static function storeImage($file, $folder = 'website'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }
        
        // Store in public disk
        $path = $file->store($folder, 'public');
        
        // Return path without 'public/' prefix
        return str_replace('public/', '', $path);
    }
}