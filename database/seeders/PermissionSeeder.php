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

            ['id' => 5, 'name' => 'create-region', 'display_name' => 'إنشاء منطقة'],
            ['id' => 6, 'name' => 'destroy-region', 'display_name' => 'حذف منطقة'],
            ['id' => 7, 'name' => 'view-region', 'display_name' => 'عرض منطقة'],
            ['id' => 8, 'name' => 'update-region', 'display_name' => 'تعديل منطقة'],
            
            ['id' => 9, 'name' => 'create-ad', 'display_name' => 'إنشاء إعلان'],
            ['id' => 10, 'name' => 'destroy-ad', 'display_name' => 'حذف إعلان'],
            ['id' => 11, 'name' => 'view-ad', 'display_name' => 'عرض إعلان'],
            ['id' => 12, 'name' => 'update-ad', 'display_name' => 'تعديل إعلان'],

            ['id' => 13, 'name' => 'create-saleOption', 'display_name' => 'إنشاء خيار بيع'],
            ['id' => 14, 'name' => 'destroy-saleOption', 'display_name' => 'حذف خيار بيع'],
            ['id' => 15, 'name' => 'view-saleOption', 'display_name' => 'عرض خيار بيع'],
            ['id' => 16, 'name' => 'update-saleOption', 'display_name' => 'تعديل خيار بيع'],

            ['id' => 17, 'name' => 'create-comment', 'display_name' => 'إنشاء تعليق'],
            ['id' => 18, 'name' => 'destroy-comment', 'display_name' => 'حذف تعليق '],
            ['id' => 19, 'name' => 'view-comment', 'display_name' => 'عرض  تعليق'],
            ['id' => 20, 'name' => 'update-comment', 'display_name' => 'تعديل تعليق '],

            ['id' => 21, 'name' => 'create-role', 'display_name' => 'إنشاء دور'],
            ['id' => 22, 'name' => 'destroy-role', 'display_name' => 'حذف دور '],
            ['id' => 23, 'name' => 'view-role', 'display_name' => 'عرض  دور'],
            ['id' => 24, 'name' => 'update-role', 'display_name' => 'تعديل دور '],

            ['id' => 25, 'name' => 'create-user', 'display_name' => 'إنشاء مستخدم'],
            ['id' => 26, 'name' => 'destroy-user', 'display_name' => 'حذف مستخدم '],
            ['id' => 27, 'name' => 'view-user', 'display_name' => 'عرض  مستخدم'],
            ['id' => 28, 'name' => 'update-user', 'display_name' => 'تعديل مستخدم '],

            ['id' => 29, 'name' => 'create-bid', 'display_name' => 'إنشاء سوم'],
            ['id' => 30, 'name' => 'destroy-bid', 'display_name' => 'حذف سوم '],
            ['id' => 31, 'name' => 'view-bid', 'display_name' => 'عرض  سوم'],
            ['id' => 32, 'name' => 'update-bid', 'display_name' => 'تعديل سوم '],
        ];
        DB::table('permissions')->insert($Permissions);
    }
}
