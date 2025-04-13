<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pengguna</title>

        <!-- Google Font: Source Sans Pro -->
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallb
ack">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('Adminlte/plugins/fontawesome-free/css/all.min.css') }}">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('Adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
 <!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('Adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('Adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            
        </div>
        <div class="card-body">
            <p class="login-box-msg">Silahkan login untuk mulai session</p>
            <form id="form-login" action="{{ route('login.aksi') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <small class="username_error error-text text-danger"></small>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <small class="password_error error-text text-danger"></small>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Member Me</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('Adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('Adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('Adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('Adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('Adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('Adminlte/dist/js/adminlte.min.js') }}"></script>
<script>
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$(document).ready(function() {
$("#form-login").validate({
rules: {
username: {required: true, minlength: 4, maxlength: 20},
password: {required: true, minlength: 6, maxlength: 20}
},
submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan
$.ajax({
url: form.action,
type: form.method,
data: $(form).serialize(),
success: function(response) {
if(response.status){ // jika sukses
Swal.fire({
icon: 'success',
title: 'Berhasil',
text: response.message,
}).then(function() {
window.location = response.redirect;
});
}else{ // jika error
$('.error-text').text('');
$.each(response.msgField, function(prefix, val) {
$('#error-'+prefix).text(val[0]);
});
Swal.fire({
icon: 'error',
title: 'Terjadi Kesalahan',
text: response.message
});
}
}
});
return false;
},
errorElement: 'span',
errorPlacement: function (error, element) {
error.addClass('invalid-feedback');
element.closest('.input-group').append(error);
},
highlight: function (element, errorClass, validClass) {
$(element).addClass('is-invalid');
},
unhighlight: function (element, errorClass, validClass) {
$(element).removeClass('is-invalid');
}
});
});
</script></body>
</html>
