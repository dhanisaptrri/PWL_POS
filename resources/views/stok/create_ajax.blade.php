<form action="{{ url('/stok/store_ajax') }}" method="POST" id="form-tambah-stok">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Input -->
                <div class="form-group">
                    <label for="barang_id">Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control select2" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $item)
                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="supplier_id">Nama Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($supplier as $item)
                            <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="stok_jumlah">Jumlah</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" required>
                    <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="stok_tanggal">Tanggal</label>
                    <input type="date" name="stok_tanggal" id="stok_tanggal" class="form-control" required>
                    <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
    // Validasi Form Tambah Stok
    $("#form-tambah-stok").validate({
        rules: {
            barang_id: {
                required: true
            },
            supplier_id: {
                required: true
            },
            stok_jumlah: {
                required: true,
                min: 1
            },
            stok_tanggal: {
                required: true
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        // Hide the modal
                        $('#myModal').modal('hide');  // Ensure you are hiding the correct modal

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonColor: '#3085d6', // Customize the confirm button
                            background: '#f7f9fc', // Set background color
                        });

                        // Reload DataTable (check if the variable 'dataUser' is defined)
                        if (typeof dataUser !== 'undefined') {
                            dataUser.ajax.reload();  // Refresh the table
                        } else {
                            console.warn('dataUser DataTable is not defined');
                        }

                    } else {
                        // Show validation errors
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message,
                            confirmButtonColor: '#d33', // Customize confirm button
                            background: '#f8d7da', // Set background color for error
                        });
                    }
                },
                error: function(xhr) {
                    // General AJAX error handling
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan, coba lagi nanti.',
                        confirmButtonColor: '#d33', // Customize confirm button
                        background: '#f8d7da', // Set background color for error
                    });
                }
            });
            return false;  // Prevent the default form submission
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
