<?php

namespace   App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'm_supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];

    public $timestamps = false;

    protected $guarded = [];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'supplier_id', 'supplier_id');
    }
}