<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
        ->addColumn('aksi', function($barang){
            $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
    
    public function create_ajax()
    {
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori
        return view('barang.create_ajax', ['kategori' => $kategori]);
    }

    public function store_ajax(Request $request)
    {
    
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:50',
                'kategori_id' => 'required',
                'barang_satuan' => 'required|string|max:10',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            BarangModel::create([
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'kategori_id' => $request->kategori_id,
                'barang_satuan' => $request->barang_satuan,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
            ]); 

            return response()->json([
                'status' => true,
                'message' => 'Data Barang berhasil disimpan'
            ]);
        }
        return redirect('/barang');
    }

    public function edit_ajax($id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori
        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:50',
                'kategori_id' => 'required',
                'barang_satuan' => 'required|string|max:10',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $barang = BarangModel::find($id);
            $barang->update([
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'kategori_id' => $request->kategori_id,
                'barang_satuan' => $request->barang_satuan,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
            ]); 
            return response()->json([
                'status' => true,
                'message' => 'Data Barang berhasil diubah'
            ]);
        }
        return redirect('/barang');
    }

    public function show_ajax($id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all(); //ambil data kategori untuk filter kategori
        return view('barang.show_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }       

    public function confirm_ajax($id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax($id)
    {
        $barang = BarangModel::find($id);
        $barang->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data Barang berhasil dihapus'
        ]);
    }
}
      