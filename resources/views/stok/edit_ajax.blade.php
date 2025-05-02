@empty($stok)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data stok yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="stok_id">Stok ID</label>
                    <input type="text" name="stok_id" id="stok_id" class="form-control" value="{{ $stok->stok_id }}" placeholder="Kode Stok" readonly>
                    <span class="error-text" id="error-stok_id"></span>
                </div>
                <div class="form-group">
                    <label for="barang_id">Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}" {{ $stok->barang_id == $item->barang_id ? 'selected' : '' }}>{{ $item->barang_nama }}</option>
                        @endforeach
                    </select>
                    <span class="error-text" id="error-barang_id"></span>
                </div>
                <div class="form-group">
                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        @foreach($supplier as $item)
                            <option value="{{ $item->supplier_id }}" {{ $stok->supplier_id == $item->supplier_id ? 'selected' : '' }}>
                                {{ $item->supplier_nama }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error-text" id="error-supplier_id"></span>
                </div>

                <div class="form-group">
                    <label for="stok_jumlah">Jumlah Stok</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" value="{{ $stok->stok_jumlah }}" placeholder="Jumlah Stok" required>
                    <span class="error-text" id="error-stok_jumlah"></span>
                </div>
                <div class="form-group">
                    <label for="stok_tanggal">Tanggal</label>
                    <input type="date" name="stok_tanggal" id="stok_tanggal" class="form-control" value="{{ $stok->stok_tanggal->format('Y-m-d') }}" required>
                    <span class="error-text" id="error-stok_tanggal"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                barang_id: {
                    required: true
                },
                stok_jumlah: {
                    required: true,
                    min: 1
                },
                stok_tanggal: {
                    required: true,
                    date: true
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataStok.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            if (response.errors) {
                                // Jika ada error validasi, tampilkan di bawah input terkait
                                $.each(response.errors, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }

                            // Tampilkan popup error
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: `
                                    <p>${response.message}</p>
                                    <ul>
                                        ${response.errors ? Object.values(response.errors).map(err => `<li>${err[0]}</li>`).join('') : ''}
                                    </ul>
                                `
                            });
                        }
                    },
                    error: function(xhr) {
                        // Tampilkan error jika stok tidak bisa dikurangi
                        if (xhr.status === 422) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Jumlah stok tidak boleh dikurangi. Hanya bisa ditambah.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan pada server.'
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty