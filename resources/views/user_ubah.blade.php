<body>
    <h1>Form Ubah Data User</h1>
    <form method="post" action="/user/ubah_simpan/{{ $data->user_id }}">
        {{ csrf_field() }}

        <label>Username:</label>
        <input type="text" name="username" value="{{ $data->username }}" placeholder="Masukkan Username">
        <br>

        <label>Nama:</label>
        <input type="text" name="nama" value="{{ $data->nama }}" placeholder="Masukkan Nama">
        <br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Masukkan Password (kosongkan jika tidak diubah)">
        <br>

        <label>Level ID:</label>
        <input type="number" name="level_id" value="{{ $data->level_id }}" placeholder="Masukkan ID Level">
        <br>

        <input type="submit" class="btn btn-success" value="Update">
    </form>
</body>
