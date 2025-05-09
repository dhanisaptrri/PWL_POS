<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\PDF; 
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Menampilkan halaman daftar user.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // Menandai menu yang sedang aktif
        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    /**
     * Mengambil data user dalam bentuk JSON untuk DataTables.
     */
  public function list(Request $request)
   {
      $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
         ->with('level');

      // Filter data user berdasarkan level_id
      if ($request->level_id) {
         $users->where('level_id', $request->level_id);
      }

      return DataTables::of($users)
         ->addIndexColumn() // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
         ->addColumn('aksi', function ($user) { // Menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
         })
         ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
         ->make(true);
   }
     public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru',
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil ditambahkan');
    }
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail'],
        ];

        $page = (object) [
            'title' => 'Detail user',
        ];

        $activeMenu = 'user';

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }
     public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user',
        ];

        $activeMenu = 'user';

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        $user = UserModel::find($id);

        $userData = [
            'username' => $request->username,
            'nama' => $request->nama,
            'level_id' => $request->level_id,
        ];

        if ($request->password) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        return redirect('/user')->with('success', 'Data user berhasil diperbarui');
    }
    public function destroy($id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'User not found');
        }

        try {
            UserModel::destroy($id);

            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Terjadi kesalahan, data user gagal dihapu karena masih terdapat data yang terkait');
        }
    }

    public function create_ajax() {
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax')
            ->with('level', $level); 
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        return redirect('/user');
    }

    public function edit_ajax(string$id) {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
{
    // Cek apakah request berasal dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, // Respon JSON, true: berhasil, false: gagal
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors() // Menunjukkan field mana yang error
            ]);
        }

        // Cek apakah data user ditemukan
        $check = UserModel::find($id);
        if ($check) {
            if (!$request->filled('password')) {
                $request->request->remove('password');
            }

            $data = $request->except(['password']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            $check->update($data);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    
    return redirect('/');
}

    public function show_ajax(string $id) {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.show_ajax', ['user' => $user, 'level' => $level]);
    }


    public function confirm_ajax(string $id) {
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        //cek apakah req dari ajax
        if($request->ajax() || $request->wantsJson()){
            $user = UserModel::find($id);
            if($user){
                try {
                    $user->delete();
                return response()->json([
                    'status'=> true,
                    'message'=> 'Data berhasil dihapus'
                ]);
                } catch (\Throwable $th) {
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data tidak bisa dihapus'
                ]);
                }
                
            }else{
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data tidak ditemuka'
                ]);
            }
        } 
        return redirect('/');   
    }

     public function import() {
         return view('user.import');
     }
 
     public function import_ajax(Request $request) {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'file_user' => 'required|mimes:xlsx,xls|max:1024'
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
             try {
                 $file = $request->file('file_user');
 
                 $reader = IOFactory::createReader('Xlsx');
                 $reader->setReadDataOnly(true);
                 $spreadsheet = $reader->load($file->getRealPath());
                 $sheet = $spreadsheet->getActiveSheet();
 
                 $data = $sheet->toArray(null, false, true, true);
 
                 $insert = [];
                 if (count($data) > 1) {
                     foreach ($data as $baris => $value) {
                         if ($baris > 1) {
                             $insert[] = [
                                 'level_id' => $value['A'],
                                 'username' => $value['B'],
                                 'nama' => $value['C'],
                                 'password' => Hash::make($value['D']),
                                 'created_at' => now()
                             ];
                         }
                     }
                     if (count($insert) > 0) {
                         UserModel::insertOrIgnore($insert);
                     }
 
                     return response()->json([
                         'status' => true,
                         'message' => 'Data berhasil diimport'
                     ]);
                 } else {
                     return response()->json([
                         'status' => false,
                         'message' => 'Data tidak ditemukan'
                     ]);
                 }
             } catch (\Exception $e) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data gagal diimport, silahkan coba lagi'
                 ]);
             }
         }
         return redirect('/');
     }
    
     public function export_excel() {
        // ambil data user yang akan diexport
        $user = UserModel::select('level_id', 'username', 'nama', 'password')->get();
    
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Level ID');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Password');
    
        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // set bold pada header
    
        $no = 1;
        $baris = 2;
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->level_id);
            $sheet->setCellValue('C' . $baris, $value->username);
            $sheet->setCellValue('D' . $baris, $value->nama);
            $sheet->setCellValue('E' . $baris, $value->password);
            $no++;
            $baris++;
        }
    
        // set lebar kolom
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // set nama file
        $sheet->setTitle('Data User');
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    
        $writer->save('php://output'); // simpan file ke output
        exit; // hentikan script setelah file di download
    }
    public function export_pdf()
    {
        $user = userModel::select('user_id', 'username', 'nama', 'level_id')  
            ->orderBy('user_id')
            ->orderBy('username')
            ->orderBy('nama')
            ->orderBy('level_id')
            ->with('level')
            ->get();      
        $pdf = PDF::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data User '.date('Y-m-d H:i:s').'.pdf');
    }

    
}