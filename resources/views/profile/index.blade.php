@extends('layouts.template')
 
 @section('content')
 <div class="container mt-4">
     <div class="card">
         <div class="card-header">
             <h4>Profil Saya</h4>
         </div>
         <div class="card-body">
             @if (session('success'))
                 <div class="alert alert-success">{{ session('success') }}</div>
             @endif
 
             <div class="text-center mb-3">
                 @if($user->foto)
                     <img src="{{ asset('uploads/foto_user/' . $user->foto) }}" alt="Foto Profil" width="150" class="rounded-circle">
                 @else
                     <img src="{{ asset('default-user.png') }}" alt="Foto Profil" width="150" class="rounded-circle">
                 @endif
             </div>
 
             <table class="table table-bordered table-striped table-hover table-sm">
                 <tr>
                     <th>ID</th>
                     <td>{{ $user->user_id }}</td>
                 </tr>
                 <tr>
                     <th>Level</th>
                     <td>{{ $user->level->level_nama }}</td>
                 </tr>
                 <tr>
                     <th>Nama</th>
                     <td>{{ $user->nama }}</td>
                 </tr>
                 <tr>
                     <th>Username</th>
                     <td>{{ $user->username }}</td>
                 </tr>
             </table>
             <br>
 
             <form action="{{ route('profile.updateFoto') }}" method="POST" enctype="multipart/form-data" id="form-foto">
                 @csrf
                 <div class="form-group">
                     <label>Ubah Foto Profil</label>
                     <input type="file" name="foto" class="form-control" required>
                     @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                 </div>
                 <button type="submit" class="btn btn-primary">Upload</button>
             </form>
         </div>
     </div>
 </div>
 @endsection