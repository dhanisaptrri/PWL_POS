<?php   

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory; 

    protected $table = 'm_supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];

    public $timestamps = false;

  // di SupplierModel.php
public function barang()
{
    return $this->hasMany(BarangModel::class, 'id_supplier', 'supplier_id');
}


}
