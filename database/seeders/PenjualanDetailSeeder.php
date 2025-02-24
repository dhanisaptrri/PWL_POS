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
        // Pastikan ID penjualan dan barang ada di database
        $penjualanIds = DB::table('t_penjualan')->pluck('penjualan_id')->toArray();
        $barangIds = DB::table('m_barang')->pluck('barang_id')->toArray();
        
        if (empty($penjualanIds) || empty($barangIds)) {
            Log::warning('Seeder gagal: Tidak ada data di t_penjualan atau m_barang.');
            return;
        }
        
        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'penjualan_id' => $penjualanIds[array_rand($penjualanIds)],
                'barang_id' => $barangIds[array_rand($barangIds)],
                'harga' => rand(5000, 50000), // Harga acak antara 5.000 - 50.000
                'jumlah' => rand(1, 10), // Jumlah acak antara 1 - 10
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}
