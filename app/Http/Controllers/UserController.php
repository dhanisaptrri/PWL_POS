<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = UserModel::firstOrNew(
            ['username' => 'manager33'], // Cari berdasarkan username
            [
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]
        );
    
        if (!$user->exists) { // Cek apakah user sudah ada di database
            $user->save();
        }
    
        return view('user', ['data' => $user]);
    }
    
}
