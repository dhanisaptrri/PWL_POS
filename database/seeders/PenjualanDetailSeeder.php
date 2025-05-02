<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data dari t_penjualan
        $penjualan = DB::table('t_penjualan')->get();
        $data = [

            ['penjualan_id' => 1,
            'barang_id' => 1,
            'harga' => 10000,
            'jumlah' => 2,
        ],
            ['penjualan_id' => 1,
            'barang_id' => 2,
            'harga' => 20000,
            'jumlah' => 1,
        ],
            ['penjualan_id' => 2,
            'barang_id' => 3,
            'harga' => 15000,
            'jumlah' => 3,
        ],
            ['penjualan_id' => 2,
            'barang_id' => 4,
            'harga' => 25000,
            'jumlah' => 1,
        ],
            ['penjualan_id' => 3,
            'barang_id' => 5,
            'harga' => 30000,
            'jumlah' => 2,
        ]
        ];
        DB::table('t_penjualan_detail')->insert($data);
    }
}
