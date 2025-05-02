<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'Admin',
                'nama' => 'Administrator',
                'password' => Hash::make('1234567'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'Manager',
                'nama' => 'Manager',
                'password' => Hash::make('1234567'),
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'Staff',
                'nama' => 'Staff/Kasir',
                'password' => Hash::make('1234567'),
            ],
        ];

        DB::table('m_user')->insert($data);
    }
}