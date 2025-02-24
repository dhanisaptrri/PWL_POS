<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // $data = [
        //     'kategori_kode' => 'SNK',
        //     'kategori_nama' => 'Snack/Makanan Ringan',
        //     'created_at' => now()
        // ];

        // try {
        //     // Cek apakah kategori sudah ada
        //     $exists = DB::table('m_kategori')->where('kategori_kode', $data['kategori_kode'])->exists();

        //     if (!$exists) {
        //         // Jika belum ada, insert data baru
        //         DB::table('m_kategori')->insert($data);
        //         return 'Insert data baru berhasil';
        //     } else {
        //         // Jika sudah ada, update data yang ada
        //         DB::table('m_kategori')->where('kategori_kode', $data['kategori_kode'])
        //             ->update(['kategori_nama' => $data['kategori_nama'], 'created_at' => now()]);

        //         return 'Data sudah ada, berhasil diperbarui.';
        //     }
        // } catch (\Illuminate\Database\QueryException $e) {
        //     return 'Gagal menambahkan data: ' . $e->getMessage();
        // }

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        // return 'Update data berhasil. Jumlah data yang diupdate: '.$row.' baris';

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        // return 'Delete data berhasil. Jumlah data yang dihapus: '.$row.' baris';

        $data = DB::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);
    }
}
