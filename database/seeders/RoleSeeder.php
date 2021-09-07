<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['superadmin', 'keuangan', 'ticketing', 'owner'];
        $permission_type = ['create', 'edit', 'delete'];
        $permission_data = ['city'];
        foreach ($roles as $key => $value) {
            Role::create([
                'name'          => $value,
                'guard_name'    => 'web'
            ]);
        }
        foreach ($permission_data as $key => $value1) {
            foreach ($permission_type as $key => $value) {
                Permission::create([
                    'name'          => $value1 . $value
                ]);
            }
        }
    }
}
