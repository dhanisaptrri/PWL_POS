<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 3, // Sesuaikan dengan ID user yang ada
                'pembeli' => 'Shisil',
                'penjualan_kode' => Str::upper(Str::random(10)),
                'penjualan_tanggal' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Sesuaikan dengan ID user yang ada
                'pembeli' => 'Adibong',
                'penjualan_kode' => Str::upper(Str::random(10)),
                'penjualan_tanggal' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Sesuaikan dengan ID user yang ada
                'pembeli' => 'Wiwink',
                'penjualan_kode' => Str::upper(Str::random(10)),
                'penjualan_tanggal' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('t_penjualan')->insert($data);
    }
}