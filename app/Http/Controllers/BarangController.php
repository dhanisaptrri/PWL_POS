<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Hash;
 use PhpOffice\PhpSpreadsheet\IOFactory;
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
    $data = BarangModel::with('kategori')->select('barang_id','barang_kode', 'barang_nama', 'harga_beli', 'harga_jual','kategori_id');

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

    public function import(){
        return view('barang.import');
    }

    public function import_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_barang' => 'required', 'mimes:xls,xlsx', 'max:1024',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, 
                    'message'  =>'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $file = $request->file('file_barang'); // ambil file dari request

            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // set reader hanya membaca data saja
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet aktif

            $data = $sheet->toArray(null, false, true, true); //ambil data excel

            $insert = [];
            if(count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value){
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli' => $value['D'],
                            'harga_jual' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // inseert data ke database, jika data sudah ada, maka diabaikan
                    BarangModel::insertOrIgnore($insert); // insert data ke database
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/barang');
    }

    public function export_excel(){
        // ambil data barang yang aan di export
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No'); 
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // set bold pada header

        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari 2
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang_kode);
            $sheet->setCellValue('C' . $baris, $value->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga_beli);
            $sheet->setCellValue('E' . $baris, $value->harga_jual);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama); // ambil nama kategori
            $no++; // increment nomor data
            $baris++; // increment baris data
        }

        // set lebar kolom
        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto sizs untuk kolom
        }

        // set nama file
        $sheet->setTitle('Data Barang'); // set judul sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
        $filename = 'Data_Barang_' . date('Y-m-d_H-i-s') . '.xlsx'; 

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output'); // simpan file ke output
        exit; // hentikan script setelah file di download
    }
}