<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =[
            [
                'barang_id' => 1,
                'supplier_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 2,
                'supplier_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 3,
                'supplier_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 4,
                'supplier_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 5,
                'supplier_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 6,
                'supplier_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 7,
                'supplier_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 8,
                'supplier_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 9,
                'supplier_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 10,
                'supplier_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 11,
                'supplier_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 12,
                'supplier_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 13,
                'supplier_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 14,
                'supplier_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'barang_id' => 15,
                'supplier_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

            DB::table('t_stok')->insert($data);
    }
}
