<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PenjualanModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan'; // Nama tabel di database
    protected $primaryKey = 'penjualan_id';
    public $timestamps = false; // Jika tabel tidak memiliki created_at & updated_at        

    protected $fillable = [
        'penjualan_id',
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    ];

    protected $casts = [
        'penjualan_tanggal' => 'datetime',
    ];  

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function detail()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id', 'penjualan_id');
    }

}