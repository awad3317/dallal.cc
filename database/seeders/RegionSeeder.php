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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('regions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('regions')->truncate();
        $regions = [
            [
                'name' => 'جدة',
                'latitude' => 21.2854,
                'longitude' => 39.1269,
                'parent_id' => null,
                'neighborhoods' => [
                    ['name' => 'الصفا', 'latitude' => 21.2990, 'longitude' => 39.1560],
                    ['name' => 'البغدادية', 'latitude' => 21.2771, 'longitude' => 39.1532],
                    ['name' => 'النزلة اليمانية', 'latitude' => 21.2974, 'longitude' => 39.1443],
                    ['name' => 'النزلة الشرقية', 'latitude' => 21.2885, 'longitude' => 39.1622],
                    ['name' => 'الشرفية', 'latitude' => 21.2902, 'longitude' => 39.1478],
                    ['name' => 'الكندرة', 'latitude' => 21.2351, 'longitude' => 39.1558],
                    ['name' => 'السلامة', 'latitude' => 21.2896, 'longitude' => 39.1601],
                    ['name' => 'المحمدية', 'latitude' => 21.2855, 'longitude' => 39.1426],
                    ['name' => 'الروضة', 'latitude' => 21.2842, 'longitude' => 39.1690],
                    ['name' => 'الحمراء', 'latitude' => 21.2962, 'longitude' => 39.1152],
                    ['name' => 'الاستقلال', 'latitude' => 21.2671, 'longitude' => 39.1772],
                    ['name' => 'البرج', 'latitude' => 21.2840, 'longitude' => 39.1989],
                    ['name' => 'العزيزية', 'latitude' => 21.4094, 'longitude' => 39.6617],
                    ['name' => 'الشرايع', 'latitude' => 21.4052, 'longitude' => 39.7302],
                    ['name' => 'السبعين', 'latitude' => 21.5552, 'longitude' => 39.2126],
                    ['name' => 'الفيصلية', 'latitude' => 21.2158, 'longitude' => 39.1677],
                    ['name' => 'الغمدة', 'latitude' => 21.1645, 'longitude' => 39.4711],
                    ['name' => 'النزهة', 'latitude' => 21.2907, 'longitude' => 39.1652],
                ],
            ],
            [
                'name' => 'الرياض',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'parent_id' => null,
                'neighborhoods' => [
                    ['name' => 'الملز', 'latitude' => 24.7265, 'longitude' => 46.6984],
                    ['name' => 'العليا', 'latitude' => 24.6836, 'longitude' => 46.6864],
                    ['name' => 'الربوة', 'latitude' => 24.6880, 'longitude' => 46.7621],
                    ['name' => 'النسيم', 'latitude' => 24.7033, 'longitude' => 46.7222],
                    ['name' => 'الشهداء', 'latitude' => 24.7099, 'longitude' => 46.7062],
                    ['name' => 'الفروانية', 'latitude' => 24.6849, 'longitude' => 46.6761],
                    ['name' => 'العزيزية', 'latitude' => 24.6596, 'longitude' => 46.7793],
                    ['name' => 'السويدي', 'latitude' => 24.5787, 'longitude' => 46.5832],
                    ['name' => 'الصحافة', 'latitude' => 24.7694, 'longitude' => 46.6830],
                    ['name' => 'السلام', 'latitude' => 24.8019, 'longitude' => 46.8832],
                    ['name' => 'الدرعية', 'latitude' => 24.6284, 'longitude' => 46.5404],
                    ['name' => 'الفيصلة', 'latitude' => 24.6884, 'longitude' => 46.7932],
                    ['name' => 'القيروان', 'latitude' => 24.8175, 'longitude' => 46.6723],
                    ['name' => 'الخزامى', 'latitude' => 24.7123, 'longitude' => 46.5830],
                    ['name' => 'العليا', 'latitude' => 24.6836, 'longitude' => 46.6864],
                    ['name' => 'البديعة', 'latitude' => 24.6654, 'longitude' => 46.5512],
                    ['name' => 'الدرعية', 'latitude' => 24.6282, 'longitude' => 46.5330],
                ]
            ],
            [
                'name' => 'مكة المكرمة',
                'latitude' => 21.4225,
                'longitude' => 39.8262,
                'parent_id' => null,
                'neighborhoods' => [
                    ['name' => 'العزيزية', 'latitude' => 21.3960, 'longitude' => 39.8268],
                    ['name' => 'المسفلة', 'latitude' => 21.4328, 'longitude' => 39.8450],
                    ['name' => 'الشرائع', 'latitude' => 21.4121, 'longitude' => 39.7250],
                    ['name' => 'الطفيل', 'latitude' => 21.4200, 'longitude' => 39.8800],
                    ['name' => 'الحجون', 'latitude' => 21.3966, 'longitude' => 39.8461],
                    ['name' => 'مكة المكرمة الجديدة', 'latitude' => 21.3582, 'longitude' => 39.8198],
                    ['name' => 'الخمرة', 'latitude' => 21.3758, 'longitude' => 39.7899],
                    ['name' => 'العوالي', 'latitude' => 21.4087, 'longitude' => 39.8312],
                    ['name' => 'السد', 'latitude' => 21.4138, 'longitude' => 39.8258],
                    ['name' => 'جبل الكعبة', 'latitude' => 21.4190, 'longitude' => 39.8260],
                    ['name' => 'الحسينية', 'latitude' => 21.4122, 'longitude' => 39.8277],
                    ['name' => 'المعابدة', 'latitude' => 21.4453, 'longitude' => 39.8686],
                    ['name' => 'المغاربة', 'latitude' => 21.4316, 'longitude' => 39.8234],
                    ['name' => 'الفضل', 'latitude' => 21.4161, 'longitude' => 39.8469],
                    ['name' => 'الخالدية', 'latitude' => 21.4195, 'longitude' => 39.9321],
                ]
            ],
            [
                'name' => 'الدمام',
                'latitude' => 26.4237,
                'longitude' => 50.0892,
                'parent_id' => null,
                'neighborhoods' => [
                    ['name' => 'الراكة', 'latitude' => 26.4059, 'longitude' => 50.1380],
                    ['name' => 'الخبر', 'latitude' => 26.2100, 'longitude' => 50.1971],
                    ['name' => 'قرية العليا', 'latitude' => 26.4175, 'longitude' => 50.1860],
                    ['name' => 'الفرسان', 'latitude' => 26.3855, 'longitude' => 50.1920],
                    ['name' => 'المنار', 'latitude' => 26.4650, 'longitude' => 50.0592],
                    ['name' => 'الصفا', 'latitude' => 26.4272, 'longitude' => 50.0716],
                    ['name' => 'النخيل', 'latitude' => 26.4305, 'longitude' => 50.1440],
                    ['name' => 'الواحة', 'latitude' => 26.4296, 'longitude' => 50.0905],
                    ['name' => 'الطمامة', 'latitude' => 26.4408, 'longitude' => 50.0662],
                    ['name' => 'الوسطى', 'latitude' => 26.4093, 'longitude' => 50.0833],
                    ['name' => 'الهفوف', 'latitude' => 26.3931, 'longitude' => 50.1584],
                    ['name' => 'وادي الجليد', 'latitude' => 26.4134, 'longitude' => 50.0945],
                    ['name' => 'المدينة الرياضية', 'latitude' => 26.4333, 'longitude' => 50.1133],
                    ['name' => 'السلام', 'latitude' => 26.4230, 'longitude' => 50.1340],
                ],
            ],
            [
                'name' => 'المدينة المنورة',
                'latitude' => 24.4672,
                'longitude' => 39.6142,
                'parent_id' => null,
                'neighborhoods' => [
                    ['name' => 'العوالي', 'latitude' => 24.4682, 'longitude' => 39.5764],
                    ['name' => 'المندرة', 'latitude' => 24.4968, 'longitude' => 39.5801],
                    ['name' => 'الخمرة', 'latitude' => 24.5161, 'longitude' => 39.6164],
                    ['name' => 'الجابرية', 'latitude' => 24.5099, 'longitude' => 39.5882],
                    ['name' => 'النجاح', 'latitude' => 24.4850, 'longitude' => 39.5867],
                    ['name' => 'العقبة', 'latitude' => 24.4646, 'longitude' => 39.5867],
                    ['name' => 'المصطفى', 'latitude' => 24.5162, 'longitude' => 39.5790],
                    ['name' => 'الخضراء', 'latitude' => 24.4787, 'longitude' => 39.6184],
                    ['name' => 'الحزام', 'latitude' => 24.5044, 'longitude' => 39.6143],
                    ['name' => 'وادي فاطمة', 'latitude' => 24.4980, 'longitude' => 39.5260],
                    ['name' => 'الجامعة', 'latitude' => 24.4764, 'longitude' => 39.6461],
                    ['name' => 'المستودع', 'latitude' => 24.4913, 'longitude' => 39.6365],
                    ['name' => 'البوابة', 'latitude' => 24.5326, 'longitude' => 39.6172],
                    ['name' => 'الخالدية', 'latitude' => 24.4945, 'longitude' => 39.5653],
                    ['name' => 'الأحمدية', 'latitude' => 24.5078, 'longitude' => 39.6154],
                ],
            ]
            // ... أضف بقية المناطق مثل المدينة المنورة، الدمام، الخبر، إلخ.
        ];

        
       


        foreach ($regions as $regionData) {
            $region = Region::create([
                'name' => $regionData['name'],
                'latitude' => $regionData['latitude'],
                'longitude' => $regionData['longitude'],
                'parent_id' => $regionData['parent_id'],
            ]);
            foreach ($regionData['neighborhoods'] as $neighborhood) {
                Region::create([
                    'name' => $neighborhood['name'],
                    'latitude' => $neighborhood['latitude'],
                    'longitude' => $neighborhood['longitude'],
                    'parent_id' => $region->id,
                ]);

            }
        }
    }
}