@empty($level)
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
            <a href="{{ url('/level') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="#"id="form-edit">
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Show Data Level</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Level</label>
                    <input value="{{ $level->level_kode }}" type="text" name="level_kode" id="level_kode" class="form-control" disabled>
                    <small id="error-level_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Level</label>
                    <input value="{{ $level->level_nama }}" type="text" name="level_nama" id="level_nama" class="form-control" disabled>
                    <small id="error-level_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Kembali</button>
            </div>
        </div>
    </div>
</form>
@endempty