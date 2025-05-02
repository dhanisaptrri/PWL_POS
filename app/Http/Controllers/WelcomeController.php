<?php
namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\User;
use App\Models\StokModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list'  => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';

        // Get total counts
        $totalPenjualan = PenjualanModel::count();
        $totalBarang = BarangModel::count();
        $totalKategori = KategoriModel::count();
        $totalUser = User::count();

        // Calculate total revenue
        $totalRevenue = PenjualanModel::join('t_penjualan_detail', 't_penjualan.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->sum(DB::raw('t_penjualan_detail.jumlah * t_penjualan_detail.harga'));

        // Get low stock items (less than 10)
        $lowStock = BarangModel::select('m_barang.barang_nama', 'm_barang.barang_id', DB::raw('COALESCE(SUM(t_stok.stok_jumlah), 0) as stok'))
            ->leftJoin('t_stok', 'm_barang.barang_id', '=', 't_stok.barang_id')
            ->groupBy('m_barang.barang_id', 'm_barang.barang_nama')
            ->having('stok', '<', 10)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Get sales data for chart (last 7 days)
        $salesData = PenjualanModel::select(
                DB::raw('DATE(penjualan_tanggal) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(td.jumlah * td.harga) as total_revenue')
            )
            ->join('t_penjualan_detail as td', 't_penjualan.penjualan_id', '=', 'td.penjualan_id')
            ->whereBetween('penjualan_tanggal', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top selling products
        $topProducts = PenjualanModel::join('t_penjualan_detail as td', 't_penjualan.penjualan_id', '=', 'td.penjualan_id')
            ->join('m_barang as b', 'td.barang_id', '=', 'b.barang_id')
            ->select('b.barang_nama', DB::raw('SUM(td.jumlah) as total_sold'))
            ->groupBy('b.barang_id', 'b.barang_nama')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $chartLabels = $salesData->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d M');
        });
        
        $chartData = [
            'transactions' => $salesData->pluck('total_transactions'),
            'revenue' => $salesData->pluck('total_revenue')
        ];

        return view('welcome', compact(
            'totalPenjualan',
            'totalBarang',
            'totalKategori',
            'totalUser',
            'totalRevenue',
            'lowStock',
            'topProducts',
            'chartLabels',
            'chartData',
            'breadcrumb',
            'activeMenu'
        ));
    }
}
