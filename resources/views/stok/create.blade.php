<div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('stok.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stok</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="barang_id">Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control select2" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $item)
                        <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="supplier_id">Nama Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($supplier as $item)
                        <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok_jumlah">Jumlah</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stok_tanggal">Tanggal</label>
                    <input type="date" name="stok_tanggal" id="stok_tanggal" class="form-control" required>
                </div>
                <!-- Tambahkan form group untuk stok_harga_beli jika diperlukan -->
                <div class="form-group">
                    <label for="stok_harga_beli">Harga Beli</label>
                    <input type="number" name="stok_harga_beli" id="stok_harga_beli" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
