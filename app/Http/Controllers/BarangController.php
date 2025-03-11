<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Barang yang terdaftar',
            'list'  => ['Home', 'Barang']
        ];
    
        $page = (object) [
            'title' => 'Daftar Barang'
        ];    
        $activeMenu = 'barang'; // set menu yang sedang aktif
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori
        $barang = BarangModel::all(); //ambil data barang untuk filter barang

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang'=> $barang,
            'kategori'=> $kategori,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function list(Request $request)
{
    $data = BarangModel::with('kategori')->select('barang_id','barang_kode', 'barang_nama', 'barang_satuan', 'harga_beli', 'harga_jual','kategori_id');

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('barang', function ($row) {
            return $row->barang->barang_nama ?? '-';
        })
        ->addColumn('aksi', function($row){
            $btn = '<a href="' . url('barang/' . $row->barang_id) . '" class="btn btn-info btn-sm me-1">Detail</a>';
            $btn .= '<a href="'.url('barang/'.$row->barang_id.'/edit').'" class="btn btn-sm btn-warning">Edit</a>';
            $btn .= ' <form method="POST" action="'.url('barang/'.$row->barang_id).'" style="display:inline;">';
            $btn .= csrf_field().method_field('DELETE');
            $btn .= '<button type="submit" class="btn btn-sm btn-danger">Hapus</button>';
            $btn .= '</form>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}



        public function create()
        {
            $breadcrumb = (object) [
                'title' => 'Tambah Barang',
                'list' => ['Home', 'barang', 'Tambah']
            ];
        
            $page = (object) [
                'title' => 'Tambah Barang baru',
            ];    
            $activeMenu = 'barang'; // set menu yang sedang aktif   
            $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori   
            return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]); 
        }

    public function store(Request $request)
    {
        $request->validate([    
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:50',
            'kategori_id' => 'required',
            'barang_harga' => 'required|numeric',    
            'barang_stok' => 'required|numeric',
        ]); 

        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id,
            'barang_harga' => $request->barang_harga,
            'barang_stok' => $request->barang_stok, 
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil ditambahkan');    
    }

    public function show($id)  
    {
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'barang', 'Detail']
        ];
    
        $page = (object) [
            'title' => 'Detail Barang',
        ];    
        $activeMenu = 'barang'; // set menu yang sedang aktif
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori   
        $barang = BarangModel::find($id); //ambil data barang yang akan diedit  

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'barang', 'Edit']
        ];
    
        $page = (object) [
            'title' => 'Edit Barang',
        ];    
        $activeMenu = 'barang'; // set menu yang sedang aktif
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori   
        $barang = BarangModel::find($id); //ambil data barang yang akan diedit  

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:50',
            'kategori_id' => 'required',
            'barang_harga' => 'required|numeric',    
            'barang_stok' => 'required|numeric',
        ]); 

        $barang = BarangModel::find($id);
        $barang->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id,
            'barang_harga' => $request->barang_harga,
            'barang_stok' => $request->barang_stok, 
        ]); 

        return redirect('/barang')->with('success', 'Data barang berhasil diperbarui'); 
    }

    public function destroy($id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Barang tidak ditemukan');
        }
 
        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Barang tidak bisa dihapus karena masih digunakan pada transaksi');
        }
    }   
}
      