@extends('layouts.template')

@section('content')
<div class="row">
    <!-- Total Revenue Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ url('/penjualan') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Penjualan Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPenjualan ?? 0 }}</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ url('/penjualan') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Barang Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalBarang ?? 0 }}</h3>
                <p>Total Produk</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{ url('/barang') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Kategori Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalKategori ?? 0 }}</h3>
                <p>Total Kategori</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <a href="{{ url('/kategori') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <!-- Top Products -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Produk Terlaris</h3>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($topProducts ?? [] as $product)
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">
                                {{ $product->barang_nama }}
                                <span class="badge badge-info float-right">{{ $product->total_sold }} Terjual</span>
                            </a>
                        </div>
                    </li>
                    @empty
                    <li class="item">
                        <div class="product-info text-center">
                            Tidak ada data penjualan
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stok Menipis</h3>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($lowStock ?? [] as $item)
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">
                                {{ $item->barang_nama }}
                                <span class="badge badge-danger float-right">Stok: {{ $item->stok }}</span>
                            </a>
                        </div>
                    </li>
                    @empty
                    <li class="item">
                        <div class="product-info text-center">
                            Semua stok dalam kondisi aman
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection