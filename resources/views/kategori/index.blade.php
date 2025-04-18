@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
    <div class="card">
             <div class="card-header">
                 <h3 class="card-title">{{ $page->title }}</h3>
                 <div class="card-tools">
                     <button onclick="modalAction('{{ url('kategori/import') }}')" class="btn btn-sm btn-info mt-1">Import Kategori</button>
                     <a href="{{ url('/kategori/export_excel') }}" class="btn btn-primary"><i class="fas fa-file-excel"></i> Export Excel</a>
                     <button onclick="modalAction('{{ url('kategori/create_ajax')}}')"
                         class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
                 </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori Kode</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    var dataKategori;
    $(document).ready(function() {
        dataKategori = $('#table_kategori').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kategori/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.kategori_id = $('#kategori_id').val();
                }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kategori_kode',
                        className: 'text-center'
                    },
                    {
                        data: 'kategori_nama'
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush