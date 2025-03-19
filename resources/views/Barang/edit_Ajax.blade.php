<form action="{{ url('/barang/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <input type="hidden" name="barang_id" id="edit_barang_id">
    <div id="modal-edit" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="edit_kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach($kategori as $k)
                        <option {{ ($k->kategori_id == $barang->kategori_id)? 'selected' : '' }} value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>

                        @endforeach
                    </select>
                    <small id="error-edit-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Barang</label>

                    <input value="{{ $barang->barang_kode }}" type="text" name="barang_kode" id="edit_barang_kode" class="form-control" required>
                    <small id="error-edit-barang_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input value="{{ $barang->barang_nama }}"  type="text" name="barang_nama" id="edit_barang_nama" class="form-control" required>
                    <small id="error-edit-barang_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Barang Satuan</label>
                    <input value="{{ $barang->barang_satuan }}" type="text" name="barang_satuan" id="edit_barang_satuan" class="form-control" required>
                    <small id="error-edit-barang_satuan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input value="{{ $barang->harga_beli }}" type="number" name="harga_beli" id="edit_harga_beli" class="form-control" required>
                    <small id="error-edit-harga_beli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input value="{{ $barang->harga_jual }}" type="number" name="harga_jual" id="edit_harga_jual" class="form-control" required>
                    <small id="error-edit-harga_jual" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>
