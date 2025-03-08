@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Level Pengguna</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

</div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        var dataUser = $('#table_user').DataTable({
            serverSide: true, // Menggunakan server-side processing
            ajax: {
                "url": "{{ url('user/list') }}",
                "dataType": "json",
                "type": "GET"
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "username", orderable: true, searchable: true },
                { data: "nama", orderable: true, searchable: true }, // Sesuai database
                { data: "level.level_nama", orderable: false, searchable: false }, // Sesuai relasi
                { data: "aksi", orderable: false, searchable: false }
            ]

        });
    });

    $(document).ready(function() {
    var dataUser = $('#table_user').DataTable({
        serverSide: true,
        ajax: {
            "url": "{{ url('user/list') }}",
            "type": "GET"
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "username" },
            { data: "nama" },
            { data: "level" },
            { data: "aksi", orderable: false, searchable: false }
        ]
    });

    // Event handler tombol hapus
    $('#table_user').on('click', '.btn-delete', function() {
        var userId = $(this).data('id');
        if (confirm('Yakin ingin menghapus data ini?')) {
            $.ajax({
                url: "{{ url('user') }}/" + userId,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    dataUser.ajax.reload();
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        }
    });
    $('#table_user').on('click', '.btn-delete', function() {
    var userId = $(this).data('id');
    if (confirm('Yakin ingin menghapus data ini?')) {
        $.ajax({
            url: "{{ url('user') }}/" + userId,
            type: "POST", // Ubah dari DELETE menjadi POST
            data: {
                _token: "{{ csrf_token() }}",
                _method: "DELETE" // Tambahkan method spoofing
            },
            success: function(response) {
                alert("Data berhasil dihapus");
                dataUser.ajax.reload();
            },
            error: function(xhr) {
                alert("Terjadi kesalahan: " + xhr.responseText);
            }
        });
    }
});
});

</script>
@endpush
