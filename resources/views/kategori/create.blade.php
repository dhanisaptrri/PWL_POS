@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('kategori') }}" class="form-horizontal">
            @csrf

            {{-- Kategori Kode --}}
            <div class="form-group row">                
                <label class="col-2 control-label col-form-label">Kategori Kode</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" value="{{ old('kategori_kode') }}" required>
                    @error('kategori_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror                
                </div>
            </div>

            {{-- Kategori Nama --}}
            <div class="form-group row">                
                <label class="col-2 control-label col-form-label">Nama Kategori</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" value="{{ old('kategori_nama') }}" required>
                    @error('kategori_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror                
                </div>
            </div>

            {{-- Tombol Simpan dan Kembali --}}
            <div class="form-group row">
                <div class="col-10 offset-2">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ url('kategori') }}" class="btn btn-sm btn-secondary ml-1">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
