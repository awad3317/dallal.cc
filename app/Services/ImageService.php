<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * Save the image and return its path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folder
     * @return string
     */
    public function saveImage($image, $folder = 'Primary_images',$quality = 75)
    {

        // // Generate a unique filename
        // $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();
        // $filePath = $folder . '/' . $filename;

        // // Save the file to the specified folder
        // $image->storeAs($folder, $filename, 'public');
        // // $filePath = Storage::putFileAs($folder, $image, $filename);

        // // Return the full file path
        // return 'storage/' . $filePath;
        // // return 'storage/' . $filename;

        //---------------------------------------------------------------------
        $filename = uniqid('', true) . '.webp';
        $filePath = $folder . '/' . $filename;
        $fullPath = Storage::disk('public')->path($filePath);
        Storage::disk('public')->makeDirectory($folder);
        $img = Image::make($image);
        $img->encode('webp', $quality)->save($fullPath);
        return 'storage/' . $filePath;

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