<?php

namespace Database\Seeders;

use App\Models\SaleOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $saleOptions = [
            ['name' => 'قابل للتفاوض','description'=>'السعر قابل للتفاوض مع المشتري'],
            ['name' => 'سوم','description'=>'السماح للمشترين بتقديم عروض أعلى'],
        ];

        
        foreach ($saleOptions as $option) {
            SaleOption::create($option);
        }
    }
}