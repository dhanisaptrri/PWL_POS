<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
      $user = UserModel::findOr(20,['username', 'nama'], function(){
        abort(404);
      });
        // Pastikan view 'user' ada di resources/views/user.blade.php
        return view('user', ['data' => $user]);
    }
}
