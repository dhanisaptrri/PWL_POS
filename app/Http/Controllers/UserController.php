<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = UserModel::where('level_id', 2)->count();
    //dd($users); // Debug untuk melihat data
    return view('user', ['data' => $users]);
}

}
