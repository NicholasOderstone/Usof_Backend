<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];

        $users[] = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('pass'),
            'role' => 'admin'
        ];

        // for ($i = 1; $i <= 10; $i++) {
        //     $name = "User".$i;
        //     $email = "user".$i."@example.com";
        //     $password = bcrypt("pass");
        //     $role = "user";

        //     $users[] = [
        //         'name' => $name,
        //         'email' => $email,
        //         'password' => $password,
        //         'role' => $role 
        //     ];
        // }

        \DB::table("users")->insert($users);
    }
}
