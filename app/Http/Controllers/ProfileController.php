<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\File;
 use App\Models\User;
 
 class ProfileController extends Controller
 {
     public function index()
     {
         // Set breadcrumb
         $breadcrumb = (object)[
            'title' => 'Profil Pengguna',
            'list' => ['Home', 'Profil']
        ];
 
         return view('profile.index', [
             'activeMenu' => 'profile',
             'breadcrumb' => $breadcrumb,
             'user' => Auth::user()
         ]);
     }
 
     public function updateFoto(Request $request)
     {
         $request->validate([
             'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
         ]);
 
         /** @var User $user */
         $user = Auth::user();
 
         if ($request->hasFile('foto')) {
             // Hapus foto lama jika ada
             if ($user->foto && File::exists(public_path('uploads/foto_user/' . $user->foto))) {
                 File::delete(public_path('uploads/foto_user/' . $user->foto));
             }
 
             // Simpan foto baru
             $file = $request->file('foto');
             $filename = time() . '.' . $file->getClientOriginalExtension();
             $file->move(public_path('uploads/foto_user'), $filename);
 
             $user->foto = $filename;
             $user->save();
         }
 
         return redirect()->route('profile.index')->with('success', 'Foto profil berhasil diperbarui.');
     }
 }