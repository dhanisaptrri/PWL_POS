<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = UserModel::with('level')->get();                
        return view('user', ['user' => $user]);
    }
    public function tambah() {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user');
    }

    public function ubah($id)
{
    $user = UserModel::find($id);
    if (!$user) {
        abort(404); // Jika user tidak ditemukan, tampilkan error 404
    }
    return view('user_ubah', ['data' => $user]);
}


    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->level_id = $request->level_id;
        $user->save();

        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            abort(404); // Jika user tidak ditemukan, tampilkan error 404
        }
        $user->delete();
        
        return redirect('/user');
    }




}
