<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object)[
            'title' => 'Daftar Penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $data = PenjualanModel::with(['user'])->get();

        // Tambahkan log untuk debugging
        Log::info('Data Penjualan:', $data->toArray());

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('user_name', fn($penjualan) => $penjualan->user->name ?? '-')
            ->addColumn('pembeli', fn($penjualan) => $penjualan->pembeli ?? '-')
            ->addColumn('penjualan_kode', fn($penjualan) => $penjualan->penjualan_kode ?? '-')
            ->addColumn('penjualan_tanggal', fn($penjualan) => $penjualan->penjualan_tanggal->format('d-m-Y H:i:s'))
            ->addColumn('aksi', function ($penjualan) {
                return '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah Penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembeli' => 'required|string|max:255',
            'penjualan_kode' => 'required|string|max:50|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
            'items' => 'required|json',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            $items = json_decode($request->items, true);
    
            if (empty($items)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada item yang ditambahkan.',
                ], 422);
            }
    
            DB::beginTransaction();
    
            // Create the penjualan record
            $penjualan = PenjualanModel::create([
                'user_id' => Auth::id(),
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);
    
            // Create the related penjualan items
            foreach ($items as $item) {
                $barang = BarangModel::find($item['barang_id']);
                if (!$barang || !$barang->stok || $barang->stok->stok_jumlah < $item['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => "Stok barang '{$barang->barang_nama}' tidak mencukupi.",
                    ], 422);
                }
    
                // Create penjualan detail
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'harga' => $item['harga'] ?? $barang->harga_jual,    
                    'jumlah' => $item['jumlah'],
                ]);
    
                // Decrement stock
               $stok= StokModel::where('barang_id', $item['barang_id'])->first();
                if ($stok) {
                    $stok->stok_jumlah -= $item['jumlah'];
                    $stok->save();
                }
            }
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing penjualan: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data penjualan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create_ajax()
    {
        $barang = BarangModel::with('stok')->get(); // Ambil semua barang beserta stok untuk dropdown
        return view('penjualan.create_ajax', compact('barang'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:10|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        DB::beginTransaction();
        try {
            $penjualan = new PenjualanModel();
            $penjualan->user_id = Auth::id();
            $penjualan->pembeli = $request->pembeli;
            $penjualan->penjualan_kode = $request->penjualan_kode;
            $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
            $penjualan->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan.',
                'redirect' => url('/penjualan')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing penjualan: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data penjualan.'
            ]);
        }
    }
    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['detail.barang', 'user'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:10|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
            'penjualan_tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->user_id = Auth::id();
            $penjualan->pembeli = $request->pembeli;
            $penjualan->penjualan_kode = $request->penjualan_kode;
            $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
            $penjualan->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diperbarui.',
                'redirect' => url('/penjualan')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating penjualan: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data penjualan.'
            ]);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil dihapus.',
                'redirect' => url('/penjualan')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting penjualan: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data penjualan.'
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $penjualan = PenjualanModel::with(['user', 'pelanggan'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Penjualan');

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Tanggal Penjualan');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'User');

        // Set data
        $row = 2;
        foreach ($penjualan as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->penjualan_kode);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($item->penjualan_tanggal)->format('d-m-Y H:i:s'));
            $sheet->setCellValue('D' . $row, $item->pembeli);
            $sheet->setCellValue('E' . $row, optional($item->user)->nama ?? '-');
            $row++;
        }

        // Set auto width
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        // Set filename
        $filename = 'Data_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filePath = public_path('uploads/excel/' . $filename);
        $writer->save($filePath);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
        public function export_PDF(Request $request)
    {
        $penjualan = PenjualanModel::with(['user', 'detail.barang'])->get();
        
        // Transform data to include details
        $penjualanData = [];
        foreach ($penjualan as $p) {
            foreach ($p->detail as $detail) {
                $penjualanData[] = [
                    'kode_penjualan' => $p->penjualan_kode,
                    'nama_barang' => $detail->barang->barang_nama,
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'total' => $detail->jumlah * $detail->harga
                ];
            }
        }
    
        $pdf = PDF::loadView('penjualan.export_pdf', ['penjualan' => $penjualanData]);   
        return $pdf->download('Data_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
