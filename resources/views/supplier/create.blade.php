@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Supplier</h3>
        <div class="card-tools">
            <a href="{{ url('supplier') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>
    <form action="{{ url('supplier/store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>Kode Supplier</label>
                <input type="text" name="supplier_kode" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama Supplier</label>
                <input type="text" name="supplier_nama" class="form-control" required>
            </div>

            <div class="form-group">
            <label>Alamat Supplier</label>
            <input type="text" name="supplier_alamat" class="form-control" required>
        </div>
    </div>

        

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
