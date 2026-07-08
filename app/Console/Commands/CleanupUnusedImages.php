<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Storage;

class CleanupUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup-website
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused images in website folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('Running in dry-run mode. No files will be deleted.');
        }

        // Get all images currently in use from website settings
        $usedImages = $this->getUsedImages();
        
        $this->info('Used images in website settings:');
        foreach ($usedImages as $image) {
            $this->line("  - $image");
        }
        
        // List all files in website folder
        $allFiles = Storage::disk('public')->files('website');
        
        $this->info("\nTotal files in website folder: " . count($allFiles));
        
        $filesToDelete = [];
        foreach ($allFiles as $file) {
            if (!in_array($file, $usedImages)) {
                $filesToDelete[] = $file;
            }
        }
        
        $this->info("\nFiles to delete: " . count($filesToDelete));
        
        if (count($filesToDelete) === 0) {
            $this->info('No unused files found.');
            return 0;
        }
        
        // Display files to delete
        foreach ($filesToDelete as $file) {
            $size = Storage::disk('public')->size($file);
            $sizeFormatted = $this->formatBytes($size);
            $this->line("  - $file ($sizeFormatted)");
        }
        
        if ($isDryRun) {
            $this->info("\nDry run completed. " . count($filesToDelete) . " files would be deleted.");
            return 0;
        }
        
        if (!$this->confirm("\nAre you sure you want to delete " . count($filesToDelete) . " files?")) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        // Delete files
        $deletedCount = 0;
        foreach ($filesToDelete as $file) {
            try {
                Storage::disk('public')->delete($file);
                $this->line("Deleted: $file");
                $deletedCount++;
            } catch (\Exception $e) {
                $this->error("Failed to delete $file: " . $e->getMessage());
            }
        }
        
        $this->info("\nSuccessfully deleted $deletedCount files.");
        return 0;
    }
    
    /**
     * Get all images currently in use from website settings
     */
    private function getUsedImages(): array
    {
        $usedImages = [];
        
        $imageKeys = ['logo', 'hero_image', 'about_image'];
        
        foreach ($imageKeys as $key) {
            $value = WebsiteSetting::getValue($key);
            if ($value && Storage::disk('public')->exists($value)) {
                $usedImages[] = $value;
            }
        }
        
        return $usedImages;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}