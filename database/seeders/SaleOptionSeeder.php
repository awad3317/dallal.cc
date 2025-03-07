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
        
        DB::table('sale_options')->truncate();

        
        $saleOptions = [
            ['name' => 'قابل للتفاوض'],
            ['name' => 'سوم'],
        ];

        
        foreach ($saleOptions as $option) {
            SaleOption::create($option);
        }
    }
}