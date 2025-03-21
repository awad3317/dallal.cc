<?php


namespace App\Services;

use Laravolt\Avatar\Avatar;

class AvatarService
{
    /**
     * Create an avatar and save it.
     *
     * @param string $name
     * @return string
     */
    public function createAvatar(string $name): string
    {
        // Load the package configuration from the specified path
        $config = require base_path('config/laravolt/avatar.php');

        // Set the 'rtl' value based on whether the name contains Arabic characters
        $config['rtl'] = $this->isArabic($name);

        // Create an Avatar object using the loaded configuration
        $avatar = new Avatar($config);

        // Generate a unique image name using the uniqid() function with a .png extension
        $imageName = uniqid('', true) . '.png';

        // Create the avatar based on the input name and save it to the specified folder
        $avatar->create($name)->save('images_users/' . $imageName);

        // Return the path to the saved image
        return 'images_users/' . $imageName;
    }

    /**
     * Check if the text contains Arabic characters.
     *
     * @param string $text
     * @return bool
     */
    private function isArabic(string $text): bool
    {
        return preg_match('/\p{Arabic}/u', $text);
    }

    public function isDefaultAvatar($imagePath)
    {
    
    return strpos($imagePath, 'images_users') === 0;
    }

}