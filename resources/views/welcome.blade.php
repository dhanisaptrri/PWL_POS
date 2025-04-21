@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, apa kabar!!!</h3>
        <div class="card-tools"></div>
    </div>

    <div class="card-body">
        Selamat datang semua, ini adalah halaman utama dari aplikasi ini.
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: JSON.parse('{!! json_encode($chartLabels ?? []) !!}'),
            datasets: [{
                label: 'Jumlah Transaksi',
                data: JSON.parse('{!! json_encode($chartData['transactions'] ?? []) !!}'),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                yAxisID: 'y'
            },
            {
                label: 'Pendapatan (Rp)',
                data: JSON.parse('{!! json_encode($chartData['revenue'] ?? []) !!}'),
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
