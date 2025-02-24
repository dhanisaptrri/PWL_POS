<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            
                ['kategori_kode' => 'SNK', 'kategori_nama' => 'Snack'],
                ['kategori_kode' => 'DST', 'kategori_nama' => 'Dessert'],
                ['kategori_kode' => 'DRK', 'kategori_nama' => 'Drink'],
                ['kategori_kode' => 'ICM', 'kategori_nama' => 'IceCream'],
                ['kategori_kode' => 'SDS', 'kategori_nama' => 'Sides'],
            
        ];

        DB::table('m_kategori')->insert($data);
    }
}