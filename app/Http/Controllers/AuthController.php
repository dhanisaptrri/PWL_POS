<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
 use App\Models\LevelModel;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Support\Facades\Validator;
 

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::check()){ // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
     {
         return view('auth.register');
     }
     
     public function postregister(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $validator = Validator::make($request->all(), [
                 'username' => 'required|string|min:4|max:20|unique:m_user,username',
                 'nama'     => 'required|string|max:50',
                 'password' => 'required|string|min:5|max:20',
             ]);
     
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
     
             UserModel::create([
                 'username' => $request->username,
                 'nama' => $request->nama,
                 'password' => $request->password,
                 'level_id' => 2 // default level_id untuk user biasa
             ]);
     
             return response()->json([
                 'status' => true,
                 'message' => 'Registrasi berhasil!',
                 'redirect' => url('login')
             ]);
         }
     
         return redirect('register');
     }
}
