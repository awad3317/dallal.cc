<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image;
use Buglinjo\LaravelWebp\Webp;

class ImageService
{
    /**
     * Save the image and return its path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folder
     * @return string
     */
    public function saveImage($image, $folder = 'Primary_images')
    {
         // Generate a unique filename with .webp extension
        $filename = uniqid('', true) . '.webp';
        $filePath = $folder . '/' . $filename;
    
        // Convert and save as WebP
        $webp = new Webp();
        $tempPath = $image->path();
        $webp->make($tempPath)
         ->save(storage_path('app/public/' . $filePath), 80); // 80 = جودة الصورة (0-100)
    
        // Return the full file path
        return 'storage/' . $filePath;

        // Generate a unique filename
        // $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();
        // $filePath = $folder . '/' . $filename;

        // // Save the file to the specified folder
        // $image->storeAs($folder, $filename, 'public');
        // // $filePath = Storage::putFileAs($folder, $image, $filename);

        // // Return the full file path
        // return 'storage/' . $filePath;
        // // return 'storage/' . $filename;
    }

    public function deleteImage($image){
        $baseUrl = config('app.url').'/';
        $filePath = str_replace($baseUrl, '', $image);
        $absolutePath = public_path($filePath);
        if (\File::exists($absolutePath)) {
            \File::delete($absolutePath);
            
        }
        
    }

}