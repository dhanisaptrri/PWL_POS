<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class   PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail'; // Nama tabel di database
    protected $primaryKey = 'penjualan_detail_id';
    public $timestamps = false; // Jika tabel tidak memiliki created_at & updated_at        

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'jumlah',
        'harga',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}