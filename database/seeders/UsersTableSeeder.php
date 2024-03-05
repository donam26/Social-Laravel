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
            'email' => 'hoanglai1411@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
        DB::table('users')->insert([
            'name' => 'Khải Đe',
            'email' => 'khaiden1241@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
        DB::table('users')->insert([
            'name' => 'Hoàng ai',
            'email' => 'hoanglai342@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
        DB::table('users')->insert([
            'name' => 'Khải en',
            'email' => 'khaiden243@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
        DB::table('users')->insert([
            'name' => 'Hoàn Lai',
            'email' => 'hoanglai343@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
        DB::table('users')->insert([
            'name' => 'Khả Đen',
            'email' => 'khaiden3443@gmail.com',
            'password' => Hash::make('123123'),
            'image' => 'man.jpg',
        ]);
    }
}
