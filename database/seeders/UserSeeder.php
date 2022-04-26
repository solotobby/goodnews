<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = DB::table('roles')->where('name', 'admin')->first();///Role::where('name', 'admin')->first();

        $users = [
            [
                'name' => 'Oluwatobi Solomon',
                'email' => 'solotobby@gmail.com',
                'password' => bcrypt('solomon001'),
                'phone' => '08078338748'
            ],
            [
                'name' => 'Samuel Farohunbi',
                'email' => 'farohunbi.st@gmail.com',
                'password' => bcrypt('samuel001'),
                'phone' => '08078338741'
            ],
            [
                'name' => 'Mrs Farohunbi Samuel',
                'email' => 'samuel@gmail.com',
                'password' => bcrypt('solomon001'),
                'phone' => '08078338742'
            ]
        ];

        foreach($users as $user)
        {
            $input = User::create($user); //DB::table('user')->insert($user);
            $input->assignRole($adminRole->id);
        }
    }
}
