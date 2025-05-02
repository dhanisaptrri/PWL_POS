<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 't_stok';
    protected $primaryKey = 'stok_id';
    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'stok_jumlah',
        'supplier_id',
        'tanggal_masuk'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
