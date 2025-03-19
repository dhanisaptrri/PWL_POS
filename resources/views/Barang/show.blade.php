<script>
    function showEditAjax(id) {
        $.ajax({
            url: '/barang/show_ajax/' + id,
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    let data = response.data;
                    $('#edit_barang_id').val(data.barang_id);
                    $('#edit_kategori_id').val(data.kategori_id);
                    $('#edit_barang_kode').val(data.barang_kode);
                    $('#edit_barang_nama').val(data.barang_nama);
                    $('#edit_barang_satuan').val(data.barang_satuan);
                    $('#edit_harga_beli').val(data.harga_beli);
                    $('#edit_harga_jual').val(data.harga_jual);
                    $('#modal-edit').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal mengambil data',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Terjadi kesalahan saat mengambil data barang.'
                });
            }
        });
    }
</script>
