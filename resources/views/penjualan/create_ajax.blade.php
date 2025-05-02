<form action="{{ route('penjualan.store_ajax') }}" method="POST" id="form-create-penjualan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Input -->
                <div class="form-group">
                    <label for="pembeli">Nama Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" placeholder="Nama Pembeli" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="penjualan_kode">Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" placeholder="Kode Penjualan" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="penjualan_tanggal">Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="barang_id">Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control select2">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barang as $item)
                        <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">
                            {{ $item->barang_nama }} (Stok: {{ $item->stok->stok_jumlah ?? 0 }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="text" id="harga" class="form-control" placeholder="Harga" readonly>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" class="form-control" placeholder="Jumlah">
                </div>

                <button type="button" id="add-item" class="btn btn-success">Tambah Barang</button>

                <table class="table table-bordered mt-3" id="items-table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="form-group">
                    <label for="total">Total Keseluruhan</label>
                    <input type="text" name="total" id="total" class="form-control" readonly>
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
    $(document).ready(function () {
        let items = [];
        let total = 0;

        $('#barang_id').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const harga = selectedOption.data('harga') || 0;
            $('#harga').val(harga);
        });

        $('#add-item').on('click', function () {
            const barangId = $('#barang_id').val();
            const barangNama = $('#barang_id option:selected').text();
            const harga = parseFloat($('#harga').val());
            const jumlah = parseInt($('#jumlah').val());

            if (!barangId || !harga || !jumlah || jumlah <= 0) {
                alert('Lengkapi data barang!');
                return;
            }

            const subtotal = harga * jumlah;
            items.push({ barang_id: barangId, harga, jumlah, subtotal });

            total += subtotal;
            $('#total').val(total);

            $('#items-table tbody').append(`
                <tr>
                    <td>${barangNama}</td>
                    <td>${harga}</td>
                    <td>${jumlah}</td>
                    <td>${subtotal}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                </tr>
            `);

            $('#barang_id').val('').trigger('change');
            $('#harga').val('');
            $('#jumlah').val('');
        });

        $('#items-table').on('click', '.remove-item', function () {
            const row = $(this).closest('tr');
            const index = row.index();
            total -= items[index].subtotal;
            items.splice(index, 1);
            row.remove();
            $('#total').val(total);
        });

        $('#form-create-penjualan').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serializeArray();
            formData.push({ name: 'items', value: JSON.stringify(items) });

            $.ajax({
                url: this.action,
                type: this.method,
                data: formData,
                success: function (response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        if (typeof dataPenjualan !== 'undefined') {
                            dataPenjualan.ajax.reload();
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data.'
                    });
                }
            });
        });
    });
</script>