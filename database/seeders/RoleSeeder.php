<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'id' => 2,
                'name' => 'reseller',
                'guard_name' => 'web'
            ],
            [
                'id' => 3,
                'name' => 'user',
                'guard_name' => 'web'
            ]
        ];

        foreach($roles as $role)
        {
            //Role::create($role);
            DB::table('roles')->insert($role);
        }
    }
}
