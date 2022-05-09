<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
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
                'id' => 1,
                'name' => 'Oluwatobi Solomon',
                'email' => 'solotobby@gmail.com',
                'password' => bcrypt('solomon001'),
                'phone' => '08078338748'
            ],
            [
                'id' => 2,
                'name' => 'Samuel Farohunbi',
                'email' => 'farohunbi.st@gmail.com',
                'password' => bcrypt('samuel001'),
                'phone' => '08078338741'
            ],
            [
                'id' => 3,
                'name' => 'Mrs Farohunbi Samuel',
                'email' => 'samuel@gmail.com',
                'password' => bcrypt('solomon001'),
                'phone' => '08078338742'
            ]
        ];

        Wallet::create(['user_id' => 1, 'balance' => '0', 'type' => 'admin']);

        foreach($users as $user)
        {
            $input = User::create($user); //DB::table('user')->insert($user);
            $input->assignRole($adminRole->id);
        }
    }
}
