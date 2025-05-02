<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Stok Barang Masuk',
            'list'  => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar Stok'
        ];

        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        $activeMenu = 'stok';

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $data = StokModel::with(['barang', 'supplier']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('barang_nama', fn($stok) => $stok->barang->barang_nama ?? '-')
            ->addColumn('supplier_nama', fn($stok) => $stok->supplier->supplier_nama ?? '-')
            ->addColumn('stok_tanggal', fn($stok) => $stok->stok_tanggal->format('d-m-Y'))
            ->addColumn('aksi', function($stok){
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
    
        return view('stok.create_ajax', compact('barang', 'supplier'));
    }
    
    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required',
            'supplier_id' => 'required',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            StokModel::create([
                'barang_id' => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah' => $request->stok_jumlah
            ]);
        
            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan stok: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($id)
{
    // Find the stock with all related data (eager loading)
    $stok = StokModel::with(['barang.kategori', 'supplier', 'user'])->find($id);
    
    // Check if stock exists
    if (!$stok) {
        return response()->json([
            'status' => false,
            'message' => 'Data stok tidak ditemukan.'
        ], 404);
    }
    
    // Return the view with the stock data
    return view('stok.show_ajax', compact('stok'));
}

    public function delete_ajax($id)
{
    $stok = StokModel::find($id);
    if (!$stok) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }
    
    return view('stok.confirm_ajax', compact('stok'));
}


public function destroy_ajax($id)
{
    try {
        $stok = StokModel::find($id);
        
        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Stok tidak ditemukan'
            ], 404);
        }
        
        $stok->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Stok berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        Log::error('Error deleting stok: ' . $e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Gagal menghapus stok: ' . $e->getMessage()
        ], 500);
    }
}
public function edit_ajax($id)
{
    $stok = StokModel::find($id);
    $barang = BarangModel::all();
    $supplier = SupplierModel::all();

    if (!$stok) {
        return response()->json([
            'status' => false,
            'message' => 'Data stok tidak ditemukan.'
        ], 404);
    }

    return view('stok.edit_ajax', compact('stok', 'barang', 'supplier'));
}

public function update_ajax(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'barang_id' => 'required',
        'supplier_id' => 'required',
        'stok_tanggal' => 'required|date',
        'stok_jumlah' => 'required|integer|min:1'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $stok = StokModel::find($id);

        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Data stok tidak ditemukan'
            ], 404);
        }

        // Pastikan stok baru tidak lebih kecil dari stok saat ini
        if ($request->stok_jumlah < $stok->stok_jumlah) {
            return response()->json([
                'status' => false,
                'message' => 'Jumlah stok tidak boleh dikurangi. Hanya bisa ditambah.'
            ], 422);
        }

        $stok->update([
            'barang_id' => $request->barang_id,
            'supplier_id' => $request->supplier_id,
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data stok berhasil diperbarui'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
        ], 500);
    }
}

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        $rules = [
            'file_stok' => 'required|mimes:xls,xlsx|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('file_stok');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'barang_id' => $value['A'],
                        'supplier_id' => $value['B'],
                        'user_id' => auth()->id(),
                        'stok_tanggal' => $value['D'],
                        'stok_jumlah' => $value['E'],
                        'created_at' => now()
                    ];
                }
            }

            if (count($insert)) {
                StokModel::insertOrIgnore($insert);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil diimport',
                'redirect' => url('/stok')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengimport data Stok: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export_excel()
    {
        $stok = StokModel::with(['barang', 'supplier'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Barang');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Jumlah');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($stok as $value) {
            $sheet->setCellValue("A$baris", $no++);
            $sheet->setCellValue("B$baris", $value->barang->barang_nama ?? '-');
            $sheet->setCellValue("C$baris", $value->supplier->supplier_nama ?? '-');
            $sheet->setCellValue("D$baris", $value->stok_tanggal);
            $sheet->setCellValue("E$baris", $value->stok_jumlah);
            $baris++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
{
    $stok = StokModel::with(['barang', 'supplier'])->get();
    $pdf = PDF::loadView('stok.export_pdf', compact('stok'));
    return $pdf->download('Laporan_Data_Stok.pdf');
}
}