<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Hoàng Nam',
            'email' => 'abcnam@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
    }
}
