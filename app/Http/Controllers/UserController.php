<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345'),
        ];

        // Simpan data ke database
        UserModel::create($data);

        // Ambil semua data dari tabel m_user
        $user = UserModel::all();

        // Pastikan view 'user' ada di resources/views/user.blade.php
        return view('user', ['data' => $user]);
    }
}
