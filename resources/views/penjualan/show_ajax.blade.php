<!-- filepath: c:\laragon\www\PWL_POS\resources\views\penjualan\show_ajax.blade.php -->
@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="#" id="form-show">
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input value="{{ $penjualan->penjualan_kode }}" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" disabled>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input value="{{ $penjualan->pembeli }}" type="text" name="pembeli" id="pembeli" class="form-control" disabled>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input value="{{ $penjualan->penjualan_tanggal->format('d-m-Y H:i') }}" type="text" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" disabled>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <hr>
                <h5>Detail Barang</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach ($penjualan->detail as $detail)
                        <tr>
                            <td>{{ $detail->barang->barang_nama ?? 'Barang tidak ditemukan' }}</td>
                            <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                            @php $grandTotal += $detail->harga * $detail->jumlah; @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Grand Total</th>
                            <th>{{ number_format($grandTotal, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Kembali</button>
            </div>
        </div>
    </div>
</form>
@endempty
<script>
    $(document).on('click', '.btn-show-penjualan', function () {
        const penjualanId = $(this).data('id'); // Get the ID of the penjualan

        // Show a loading spinner or message
        $('#modal-content').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');

        // Make an AJAX request to fetch the penjualan details
        $.ajax({
            url: `/penjualan/show_ajax/${penjualanId}`, // Adjust the route if necessary
            method: 'GET',
            success: function (response) {
                if (response.status) {
                    // Load the response data into the modal
                    $('#modal-content').html(response.html);
                } else {
                    // Show an error message if the request fails
                    $('#modal-content').html('<div class="alert alert-danger">Gagal memuat data penjualan.</div>');
                }
            },
            error: function () {
                // Handle errors
                $('#modal-content').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat data.</div>');
            }
        });

        // Show the modal
        $('#modal-penjualan').modal('show');
    });

    $('#t_penjualan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/penjualan/list', // Ensure this route exists
            type: 'GET',
        },
        columns: [
            { data: 'penjualan_kode', name: 'penjualan_kode' },
            { data: 'pembeli', name: 'pembeli' },
            { data: 'penjualan_tanggal', name: 'penjualan_tanggal' },
            { data: 'user.name', name: 'user.name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
    });
</script>