<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
// use Yajra\DataTables\Facades\DataTables;



class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

        public function getUsers()
    {
        $users = User::all(); // Ambil semua data user
        return response()->json(['data' => $users]); // Kirim data dalam format JSON
    }

    // Ambil data user dalam bentuk JSON untuk DataTables
 
    public function list(Request $request)
    {
        $users = UserModel::with('level')->select('user_id', 'username', 'nama', 'level_id')->get();
        
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('level', function($row) {
                return $row->level ? $row->level->level_nama : '-';
            })
            ->addColumn('aksi', function($row) {
                $btn = '<a href="'.url('user/'.$row->user_id).'" class="btn btn-sm btn-info">Detail</a> ';
                $btn .= '<a href="'.url('user/'.$row->user_id.'/edit').'" class="btn btn-sm btn-primary">Edit</a> ';
                $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$row->user_id.'">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
        
    

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb, 
            'page'       => $page, 
            'level'      => $level, 
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            // nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama'     => 'required|string|max:100', 
            // password harus diisi dan minimal 5 karakter
            'password' => 'required|min:5', 
            // level_id harus diisi dan berupa angka
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit user
public function edit(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit User',
        'list' => ['Home', 'User', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit user'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}

// Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
           'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            // username harus diisi, berupa string, minimal 3 karakter, 
            // dan unik dalam tabel user kecuali untuk user dengan id yang sedang diedit
            
            'nama' => 'required|string|max:100', 
            // nama harus diisi, berupa string, dan maksimal 100 karakter
            
            'password' => 'nullable|string|min:5', 
            // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            
            'level_id' => 'required|integer'
            // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {  // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id);  // Hapus data level
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }


}

// class UserController extends Controller
// {
//     public function index()
//     {
//         $user = UserModel::with('level')->get();                
//         return view('user', ['user' => $user]);
//     }
//     public function tambah() {
//         return view('user_tambah');
//     }

//     public function tambah_simpan(Request $request)
//     {
//         UserModel::create([
//             'username' => $request->username,
//             'nama' => $request->nama,
//             'password' => Hash::make($request->password),
//             'level_id' => $request->level_id
//         ]);

//         return redirect('/user');
//     }

//     public function ubah($id)
// {
//     $user = UserModel::find($id);
//     if (!$user) {
//         abort(404); // Jika user tidak ditemukan, tampilkan error 404
//     }
//     return view('user_ubah', ['data' => $user]);
// }


//     public function ubah_simpan($id, Request $request)
//     {
//         $user = UserModel::find($id);
//         $user->username = $request->username;
//         $user->nama = $request->nama;

//         if (!empty($request->password)) {
//             $user->password = Hash::make($request->password);
//         }

//         $user->level_id = $request->level_id;
//         $user->save();

//         return redirect('/user');
//     }

//     public function hapus($id)
//     {
//         $user = UserModel::find($id);
//         if (!$user) {
//             abort(404); // Jika user tidak ditemukan, tampilkan error 404
//         }
//         $user->delete();
        
//         return redirect('/user');
//     }




// }
