<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    protected $fillable = ['barang_kode', 'barang_nama', 'barang_satuan', 'harga_beli', 'harga_jual','kategori_id'];

    public $timestamps = false;
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }
    public function stok()
    {
        return $this->hasOne(StokModel::class, 'barang_id', 'barang_id');
    }
}
