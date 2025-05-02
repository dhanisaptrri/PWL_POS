<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model  
{
    use HasFactory;
   

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_barang';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'barang_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barang_kode',
        'barang_nama',
        'barang_harga',
        'barang_stok',
        'barang_gambar',
        'kategori_id',
        'barang_deskripsi',
    ];

    /**
     * Get the produk for the kategori.
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'barang_id');  
    }

    public function stok()
{
    return $this->hasOne(Stok::class, 'barang_id');
}
}