<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\error;

class SupplierController extends Controller
{
    
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Supplier yang terdaftar',
            'list'  => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        $supplier = SupplierModel::all(); //ambil data supplier untuk filter supplier

        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier'=> $supplier,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function list(Request $request) {
        $data = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
    
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($supplier){
                $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                    return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list'  => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::create($request->all());

        return redirect('supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);   

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Supplier',
        ];

        $activeMenu = 'supplier';

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }


    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list'  => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        $supplier = SupplierModel::find($id);

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::find($id)->update($request->all());

        return redirect('supplier')->with('success', 'Supplier berhasil diubah');
    }

    public function destroy($id)
    {
        SupplierModel::destroy($id);

        return redirect('supplier')->with('success', 'Supplier berhasil dihapus');
    }

    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Supplier berhasil ditambahkan'
        ]);
    }

    public function edit_ajax($id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.edit_ajax', compact('supplier'));
    }

    public function update_ajax(Request $request, $id)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::find($id)->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Supplier berhasil diubah'
        ]);
    }

    public function show_ajax($id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.show_ajax', compact('supplier'));
    }

    public function confirm_ajax($id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.confirm_ajax', compact('supplier'));
    }

    public function delete_ajax(Request $request, $id)
    {   
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                try {
                    $supplier->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus! (terdapat tabel lain yang terkait dengan data ini)'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/supplier');
    }

    public function import(){
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => 'required|mimes:xls,xlsx|max:1024',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            try {
                $file = $request->file('file_supplier'); // ambil file dari request

                $reader = IOFactory::createReader('Xlsx'); // load reader file excel
                $reader->setReadDataOnly(true); // set reader hanya membaca data saja
                $spreadsheet = $reader->load($file->getRealPath()); // load file excel
                $sheet = $spreadsheet->getActiveSheet(); // ambil sheet aktif

                $data = $sheet->toArray(null, false, true, true); //ambil data excel

                $insert = [];
                if (count($data) > 1) { // jika data lebih dari 1 baris
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                            $insert[] = [
                                'supplier_kode' => $value['A'],
                                'supplier_nama' => $value['B'],
                                'supplier_alamat' => $value['C'],
                                'created_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        // inseert data ke database, jika data sudah ada, maka diabaikan
                        SupplierModel::insertOrIgnore($insert); // insert data ke database
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Data Supplier berhasil diimport',
                        'redirect' => url('/'),
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengimport data Supplier, silahkan coba lagi'
                ]);
            }
        }
        return redirect('/supplier');
    }

    public function export_excel() {
        // ambil data supplier yang akan diexport
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')->get();
    
        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Supplier');
        $sheet->setCellValue('C1', 'Nama Supplier');
        $sheet->setCellValue('D1', 'Alamat Supplier');
    
        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // set bold pada header
    
        $no = 1;
        $baris = 2;
        foreach ($supplier as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier_kode);
            $sheet->setCellValue('C' . $baris, $value->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->supplier_alamat);
            $no++;
            $baris++;
        }
    
        // set lebar kolom
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // set nama file
        $sheet->setTitle('Data Supplier');
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Supplier_' . date('Y-m-d_H-i-s') . '.xlsx';
    
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