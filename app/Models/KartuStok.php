<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table="bahan_produk";

    public function stok_masuk_detail()
    {
        return $this->hasMany(DetailPembelian::class,'bahan_produk_id','id');
    }

    public function stok_keluar_detail()
    {
        return $this->hasMany(DetailPenjualan::class,'bahan_produk_id','id');
    }

}
