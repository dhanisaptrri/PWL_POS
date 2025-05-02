<form id="form-delete" action="{{ url('/stok/' . $stok->stok_id . '/delete_ajax') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Hapus</button>
    <div id="confirmModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda ingin menghapus data berikut?
                </div>  
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <td>Nama Barang</td>
                        <td>{{ $stok->barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <td>Nama Supplier</td>
                        <td>{{ $stok->supplier->supplier_nama }}</td>
                    </tr>    
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ date('d-m-Y', strtotime($stok->stok_tanggal)) }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">  
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-delete").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'DELETE',
                data: $(form).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        // Close the modal
                        $('#myModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open').css('padding-right', '');
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        
                        // Reload the data table
                        dataStok.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menghapus data. Status: ' + xhr.status
                    });
                }
            });
            return false;
        },
        // Rest of the validation code
    });
});
</script>