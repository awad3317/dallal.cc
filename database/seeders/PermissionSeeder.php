<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Permissions = [
            ['id' => 1, 'name' => 'create-categorie', 'display_name' => 'إنشاء صنف'],
            ['id' => 2, 'name' => 'destroy-categorie', 'display_name' => 'حذف صنف'],
            ['id' => 3, 'name' => 'view-categorie', 'display_name' => 'عرض صنف'],
            ['id' => 4, 'name' => 'update-categorie', 'display_name' => 'تعديل صنف'],
        ];
        DB::table('permissions')->insert($Permissions);
    }
}
