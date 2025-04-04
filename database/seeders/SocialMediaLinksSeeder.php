<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialMediaLink;

class SocialMediaLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMediaLinks = [
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/yourpage',
                'icon' => 'fab fa-facebook-f',
                'is_active' => true,
            ],
            [
                'platform' => 'Twitter',
                'url' => 'https://twitter.com/yourhandle',
                'icon' => 'fab fa-twitter',
                'is_active' => true,
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/yourprofile',
                'icon' => 'fab fa-instagram',
                'is_active' => true,
            ],
            [
                'platform' => 'LinkedIn',
                'url' => 'https://linkedin.com/company/yourcompany',
                'icon' => 'fab fa-linkedin-in',
                'is_active' => false,
            ],
            [
                'platform' => 'YouTube',
                'url' => 'https://youtube.com/yourchannel',
                'icon' => 'fab fa-youtube',
                'is_active' => true,
            ],
            [
                'platform' => 'TikTok',
                'url' => 'https://tiktok.com/@yourusername',
                'icon' => 'fab fa-tiktok',
                'is_active' => false,
            ],
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/966501234567',
                'icon' => 'fab fa-whatsapp',
                'is_active' => false,
            ],
            [
                'platform' => 'Snapchat',
                'url' => 'https://snapchat.com/add/yourusername',
                'icon' => 'fab fa-snapchat-ghost',
                'is_active' => true,
            ],
        ];

        foreach ($socialMediaLinks as $link) {
            SocialMediaLink::create($link);
        }
    
    }
}
