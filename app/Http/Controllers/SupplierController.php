<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Supplier yang terdaftar',
            'list'  => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        $supplier = SupplierModel::all(); //ambil data supplier untuk filter supplier

        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier'=> $supplier,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function list(Request $request) {
        $data = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
    
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row){
                $btn = '<a href="' . url('supplier/' . $row->supplier_id) . '" class="btn btn-info btn-sm me-1">Detail</a>';
                $btn .= '<a href="'.url('supplier/'.$row->supplier_id.'/edit').'" class="btn btn-sm btn-warning">Edit</a>';
                $btn .= ' <form method="POST" action="'.url('supplier/'.$row->supplier_id).'" style="display:inline;">';
                $btn .= csrf_field().method_field('DELETE');
                $btn .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin hapus?\')">Hapus</button>';
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list'  => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::create($request->all());

        return redirect('supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);   

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail Supplier',
        ];

        $activeMenu = 'supplier';

        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }


    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list'  => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];    
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        $supplier = SupplierModel::find($id);

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu 
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required'
        ]);

        SupplierModel::find($id)->update($request->all());

        return redirect('supplier')->with('success', 'Supplier berhasil diubah');
    }

    public function destroy($id)
    {
        SupplierModel::destroy($id);

        return redirect('supplier')->with('success', 'Supplier berhasil dihapus');
    }
    

}