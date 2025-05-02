@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Data Stok' }}</h3>
            <div class="card-tools">
            <a href="{{ url('/stok/export-pdf') }}" class="btn btn-warning"><i class="fa fa-filepdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Stok (AJAX)
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

            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Supplier</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
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
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataStok;
    $(document).ready(function () {
        dataStok = $('#table_stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('stok/list') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'barang_nama' },
                { data: 'supplier_nama' },
                { data: 'stok_tanggal', className: 'text-center' },
                { data: 'stok_jumlah', className: 'text-center' },
                { data: 'aksi', className: 'text-center', orderable: false, searchable: false }
            ]
        });

        $(document).ready(function() {
    var dataStok = $('#dataStok').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('stok.list') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'barang_nama', name: 'barang_nama' },
            { data: 'supplier_nama', name: 'supplier_nama' },
            { data: 'stok_tanggal', name: 'stok_tanggal' },
            { data: 'stok_jumlah', name: 'stok_jumlah' },
            { 
                data: 'aksi', 
                name: 'aksi', 
                orderable: false, 
                searchable: false 
            }
        ]
    });
});

// Function to handle modal actions (show in modal)
function modalAction(url) {
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#myModal').html(response).modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal memuat data: ' + xhr.status + ' ' + xhr.statusText
            });
        }
    });
}
    });
</script>
@endpush