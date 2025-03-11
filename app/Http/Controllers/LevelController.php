<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Level User yang terdaftar',
            'list'  => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level User'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        $level = LevelModel::all(); //ambil data level untuk filter level

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level'=> $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');
        //filter data user berdasarkan level_id
        if ($request->level_id) {
            $level->where('level_id', $request->level_id);
        }

        return datatables()->of($level)
            //menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<a href=" ' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href=" ' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/level/' . $level->level_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return
            confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_level' => 'required|string|max:50|unique:m_level,nama_level',
        ]);

        LevelModel::create([
            'level_nama' => $request->nama_level
        ]);

        return redirect('/level')->with('success', 'Data level berhasil ditambahkan');
    }
    public function show(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail level',
            'list' => ['Home', 'level', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail level',
        ];

        $activeMenu = 'level';

        return view('level.show', compact('level', 'breadcrumb', 'page', 'activeMenu'));
    }
     public function edit(string $id)
     {
         $level = LevelModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Edit Level',
             'list' => ['Home', 'Level', 'Edit'],
         ];
 
         $page = (object) [
             'title' => 'Edit Level',
         ];
 
         $activeMenu = 'level';
 
         return view('level.edit', compact('level', 'breadcrumb', 'page', 'activeMenu'));
     }
 
     public function update(Request $request, string $id)
     {
         $request->validate([
             'nama_level' => 'required|string|max:50|unique:m_level,nama_level,' . $id . ',level_id',
         ]);
 
         $level = LevelModel::find($id);
         $level->update([
             'nama_level' => $request->nama_level,
         ]);
 
         return redirect('/level')->with('success', 'Level berhasil diperbarui');
     }
 
     public function destroy(string $id)
     {
         $check = LevelModel::find($id);
         if (!$check) {
             return redirect('/level')->with('error', 'Level tidak ditemukan');
         }
 
         try {
             LevelModel::destroy($id);
             return redirect('/level')->with('success', 'Level berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect('/level')->with('error', 'Level gagal dihapus karena masih digunakan oleh data lain');
         }
     }
 }