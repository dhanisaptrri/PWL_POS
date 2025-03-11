@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('barang') }}" class="form-horizontal">
            @csrf

            {{-- Kode Barang --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Kode Barang</label>
                <div class="col-10">
                    <input type="text" class="form-control" name="barang_kode" value="{{ old('barang_kode') }}" required>
                    @error('barang_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Nama Barang --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Nama Barang</label>
                <div class="col-10">
                    <input type="text" class="form-control" name="barang_nama" value="{{ old('barang_nama') }}" required>
                    @error('barang_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Satuan --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Satuan</label>
                <div class="col-10">
                    <input type="text" class="form-control" name="barang_satuan" value="{{ old('barang_satuan') }}" required>
                    @error('barang_satuan')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Harga Beli --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Harga Beli</label>
                <div class="col-10">
                    <input type="number" class="form-control" name="harga_beli" value="{{ old('harga_beli') }}" required>
                    @error('harga_beli')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Harga Jual --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Harga Jual</label>
                <div class="col-10">
                    <input type="number" class="form-control" name="harga_jual" value="{{ old('harga_jual') }}" required>
                    @error('harga_jual')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Kategori --}}
            <div class="form-group row">
                <label class="col-2 col-form-label">Kategori</label>
                <div class="col-10">
                    <select name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Tombol Simpan dan Kembali --}}
            <div class="form-group row">
                <div class="col-10 offset-2">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ url('barang') }}" class="btn btn-sm btn-secondary ml-1">Kembali</a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
