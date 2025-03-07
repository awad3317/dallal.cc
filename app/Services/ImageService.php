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
        // Get the file extension
        $extension = $image->getClientOriginalExtension();

        // Generate a unique filename
        $filename = uniqid('', true) . '.' . $extension;

        // Save the file to the specified folder
        $filePath = Storage::putFileAs($folder, $image, $filename);

        // Return the full file path
        return 'storage/' . $filePath;
    }

}