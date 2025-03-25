<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole=Role::create([
            'name' => 'admin',
            'display_name' => 'أدمن'
        ]);
        $permissions = Permission::all()->pluck('id')->toArray();
        $adminRole->permissions()->attach($permissions);
        Role::create([
            'name'=>'user',
            'display_name'=>'مستخدم'
        ])->permissions()->attach([$permissions]);
    }
}
