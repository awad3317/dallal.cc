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
        $userRole = Role::create([
            'name' => 'user',
            'display_name' => 'مستخدم'
        ]);
        $permissions = Permission::all()->pluck('id')->toArray();
        if (!empty($permissions)) {
            $adminRole->permissions()->sync($permissions);
            $userRole->permissions()->sync($permissions);
        }
    }
}
