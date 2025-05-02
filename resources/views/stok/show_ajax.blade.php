<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Stok</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Stok</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th width="150">ID Stok</th>
                            <td>{{ $stok->stok_id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $stok->barang->barang_nama ?? 'Data tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $stok->barang->kategori->kategori_nama ?? 'Data tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Supplier</th>
                            <td>{{ $stok->supplier->supplier_nama ?? 'Data tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Supplier</th>
                            <td>{{ $stok->supplier->supplier_alamat ?? 'Data tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Stok</th>
                            <td>{{ date('d-m-Y', strtotime($stok->stok_tanggal)) }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ $stok->stok_jumlah }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Additional JavaScript for this specific modal if needed
});
</script>