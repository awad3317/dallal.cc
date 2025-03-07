<?php

namespace Database\Seeders;

use App\Models\region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    
    DB::table('regions')->truncate();


    $faker = \Faker\Factory::create();

    
    $mainRegions = [
        'الرياض', 'مكة المكرمة', 'المدينة المنورة', 'المنطقة الشرقية',
        'عسير', 'تبوك', 'حائل', 'الحدود الشمالية', 'جازان', 'نجران', 'الباحة', 'الجوف'
    ];

    foreach ($mainRegions as $regionName) {
        region::create([
            'name' => $regionName,
            'parent_id' => null,
        ]);
    }

    
    for ($i = 1; $i <= 20; $i++) {
        region::create([
            'name' => $faker->city,
            'parent_id' => $faker->numberBetween(1, 13),
        ]);
    }
}
}
