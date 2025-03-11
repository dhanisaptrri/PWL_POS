@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($supplier)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data supplier tidak ditemukan.
            </div>
        @else
            <div class="form-horizontal">
                {{-- Kode Supplier --}}
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Supplier</label>
                    <div class="col-10">
                        <p class="form-control-plaintext">{{ $supplier->supplier_kode }}</p>
                    </div>
                </div>

                {{-- Nama Supplier --}}
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Supplier</label>
                    <div class="col-10">
                        <p class="form-control-plaintext">{{ $supplier->supplier_nama }}</p>
                    </div>
                </div>
            </div>
        @endempty
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
