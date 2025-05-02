@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title ?? 'Data Penjualan' }}</h3>
        <div class="card-tools">
            <a href="{{ url('/penjualan/export-pdf') }}" class="btn btn-warning">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                Tambah Penjualan (AJAX)
            </button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Pembeli</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal Penjualan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal untuk form AJAX --}}
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" aria-hidden="true">
</div>
@endsection

@push('js')
<script>
    // Fungsi global agar bisa dipanggil dari onclick
    function modalAction(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('#myModal').html(response).modal('show');
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal memuat data: ' + xhr.status + ' ' + xhr.statusText
                });
            }
        });
    }

    $(document).ready(function () {
        // Inisialisasi DataTables
        $('#table_penjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('penjualan.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'user_id', name: 'user_id' },
                { data: 'pembeli', name: 'pembeli' },
                { data: 'penjualan_kode', name: 'penjualan_kode' },
                { data: 'penjualan_tanggal', name: 'penjualan_tanggal', className: 'text-center' },
                { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
