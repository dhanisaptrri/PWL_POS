<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run()
    {
        // **1. Hapus data m_user terlebih dahulu untuk menghindari constraint**
        DB::table('m_user')->delete();

        // **2. Hapus data m_level tanpa menghapus struktur tabel**
        DB::table('m_level')->delete();
        DB::statement('ALTER TABLE m_level AUTO_INCREMENT = 1'); // Reset auto-increment

        // **3. Insert data baru ke tabel m_level**
        $data = [
            ['level_id' => 1, 'level_kode' => 'ADM', 'level_nama' => 'Administrator'],
            ['level_id' => 2, 'level_kode' => 'MNG', 'level_nama' => 'Manager'],
            ['level_id' => 3, 'level_kode' => 'STF', 'level_nama' => 'Staff/Kasir'],
        ];

        DB::table('m_level')->insert($data);
    }
}