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
            'name' => 'Hoàng La',
            'email' => 'hoanglai11@gmail.com',
            'password' => Hash::make('123123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Khải Đe',
            'email' => 'khaiden11@gmail.com',
            'password' => Hash::make('123123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Hoàng ai',
            'email' => 'hoanglai2@gmail.com',
            'password' => Hash::make('123123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Khải en',
            'email' => 'khaiden2@gmail.com',
            'password' => Hash::make('123123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Hoàn Lai',
            'email' => 'hoanglai3@gmail.com',
            'password' => Hash::make('123123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Khả Đen',
            'email' => 'khaiden3@gmail.com',
            'password' => Hash::make('123123'),
        ]);
    }
}
