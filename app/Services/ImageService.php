<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;

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

        // Generate a unique filename
        $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();
        $filePath = $folder . '/' . $filename;

        // Save the file to the specified folder
        $image->storeAs($folder, $filename, 'public');
        // $filePath = Storage::putFileAs($folder, $image, $filename);

        // Return the full file path
        return 'storage/' . $filePath;
        // return 'storage/' . $filename;
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