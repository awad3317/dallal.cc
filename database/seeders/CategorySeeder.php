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
                'icon' => 'CiPizza',
            ],
            [
                'name' => 'الهواتف المحمولة',
                'parent_id' => 1,
                'icon' => 'CiPizza',
            ],
            [
                'name' => 'أجهزة اللابتوب',
                'parent_id' => 1, 
                'icon' => 'CiPizza',
            ],
            [
                'name' => 'الملابس',
                'parent_id' => null,
                'icon' => 'CiPizza',
            ],
            [
                'name' => 'ملابس الرجال',
                'parent_id' => 4, 
                'icon' => 'CiPizza.',
            ],
            [
                'name' => 'ملابس النساء',
                'parent_id' => 4, 
                'icon' => 'CiPizza',
            ],
        ];

        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}