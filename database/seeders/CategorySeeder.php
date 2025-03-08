<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('categories')->truncate();

        $categories = [
            [
                'name' => 'الإلكترونيات',
                'parent_id' => null,
                'image' => 'electronics.png',
            ],
            [
                'name' => 'الهواتف المحمولة',
                'parent_id' => 1,
                'image' => 'mobile_phones.png',
            ],
            [
                'name' => 'أجهزة اللابتوب',
                'parent_id' => 1, 
                'image' => 'laptops.png',
            ],
            [
                'name' => 'الملابس',
                'parent_id' => null,
                'image' => 'clothing.png',
            ],
            [
                'name' => 'ملابس الرجال',
                'parent_id' => 4, 
                'image' => 'mens_clothing.png',
            ],
            [
                'name' => 'ملابس النساء',
                'parent_id' => 4, 
                'image' => 'womens_clothing.png',
            ],
        ];

        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}