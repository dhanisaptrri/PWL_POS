<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Style & plugin -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>
                <form action="{{ url('register') }}" method="POST" id="form-register">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    <small class="text-danger error-text" id="error-username"></small>

                    <div class="input-group mb-3">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-id-card"></span></div>
                        </div>
                    </div>
                    <small class="text-danger error-text" id="error-nama"></small>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    <small class="text-danger error-text" id="error-password"></small>

                    <!-- Tambahan pilihan level -->
                    <div class="input-group mb-3">
                        <select name="level_kode" class="form-control">
                            <option value="">-- Pilih Level --</option>
                            <option value="ADM">Admin</option>
                            <option value="MNG">Manager</option>
                            <option value="STF">Staff / Kasir</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user-tag"></span></div>
                        </div>
                    </div>
                    <small class="text-danger error-text" id="error-level_kode"></small>

                    <div class="row">
                        <div class="col-8">
                            <a href="{{ url('login') }}" class="text-center">Sudah punya akun?</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            $('#form-register').validate({
                rules: {
                    username: { required: true, minlength: 4, maxlength: 20 },
                    nama: { required: true, minlength: 2, maxlength: 50 },
                    password: { required: true, minlength: 5, maxlength: 20 },
                    level_kode: { required: true }
                },
                messages: {
                    level_kode: "Pilih salah satu level"
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (key, value) {
                                    $('#error-' + key).text(value[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>
