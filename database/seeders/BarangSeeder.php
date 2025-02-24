<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_barang')->insert([
            // Supplier 1
            ['kategori_id' => 1, 'barang_kode' => 'SNK001', 'barang_nama' => 'Keripik Kentang', 'barang_satuan' => 'pcs', 'harga_beli' => 5000, 'harga_jual' => 7000],
            ['kategori_id' => 2, 'barang_kode' => 'DST001', 'barang_nama' => 'Cake Coklat', 'barang_satuan' => 'slice', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['kategori_id' => 3, 'barang_kode' => 'DRK001', 'barang_nama' => 'Kopi Susu', 'barang_satuan' => 'cup', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['kategori_id' => 4, 'barang_kode' => 'ICM001', 'barang_nama' => 'Es Krim Vanila', 'barang_satuan' => 'cup', 'harga_beli' => 8000, 'harga_jual' => 12000],
            ['kategori_id' => 5, 'barang_kode' => 'SDS001', 'barang_nama' => 'Kentang Goreng', 'barang_satuan' => 'porsi', 'harga_beli' => 10000, 'harga_jual' => 13000],
            
            // Supplier 2
            ['kategori_id' => 1, 'barang_kode' => 'SNK002', 'barang_nama' => 'Kacang Panggang', 'barang_satuan' => 'pcs', 'harga_beli' => 6000, 'harga_jual' => 8000],
            ['kategori_id' => 2, 'barang_kode' => 'DST002', 'barang_nama' => 'Puding Buah', 'barang_satuan' => 'cup', 'harga_beli' => 12000, 'harga_jual' => 17000],
            ['kategori_id' => 3, 'barang_kode' => 'DRK002', 'barang_nama' => 'Teh Tarik', 'barang_satuan' => 'cup', 'harga_beli' => 9000, 'harga_jual' => 14000],
            ['kategori_id' => 4, 'barang_kode' => 'ICM002', 'barang_nama' => 'Es Krim Coklat', 'barang_satuan' => 'cup', 'harga_beli' => 9000, 'harga_jual' => 13000],
            ['kategori_id' => 5, 'barang_kode' => 'SDS002', 'barang_nama' => 'Tahu Crispy', 'barang_satuan' => 'porsi', 'harga_beli' => 7000, 'harga_jual' => 10000],
            
            // Supplier 3
            ['kategori_id' => 1, 'barang_kode' => 'SNK003', 'barang_nama' => 'Popcorn', 'barang_satuan' => 'pcs', 'harga_beli' => 7000, 'harga_jual' => 10000],
            ['kategori_id' => 2, 'barang_kode' => 'DST003', 'barang_nama' => 'Donat', 'barang_satuan' => 'pcs', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['kategori_id' => 3, 'barang_kode' => 'DRK003', 'barang_nama' => 'Jus Jeruk', 'barang_satuan' => 'cup', 'harga_beli' => 8000, 'harga_jual' => 12000],
            ['kategori_id' => 4, 'barang_kode' => 'ICM003', 'barang_nama' => 'Es Krim Stroberi', 'barang_satuan' => 'cup', 'harga_beli' => 8500, 'harga_jual' => 12500],
            ['kategori_id' => 5, 'barang_kode' => 'SDS003', 'barang_nama' => 'Nugget Ayam', 'barang_satuan' => 'porsi', 'harga_beli' => 12000, 'harga_jual' => 16000],
        ]);
    }
}