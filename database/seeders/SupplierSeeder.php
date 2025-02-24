<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'SHC01',
                'supplier_nama' => 'CV. kenyang jaya',
                'supplier_alamat' => 'Jl. Salju no.4'
            ],
            [
                'supplier_kode' => 'SHC02',
                'supplier_nama' => 'PT. Perut Terisi',
                'supplier_alamat' => 'Jl. Mangga no.100'
            ],
            [
                'supplier_kode' => 'SHC03',
                'supplier_nama' => 'CV. Anti Lapar',
                'supplier_alamat' => 'Jl. ademsari no. 50'
            ],
        ];

            DB::table('m_supplier')->insert($data);
    }
}