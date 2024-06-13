<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';


    public function stok_keluar()
    {
        return $this->belongsTo(KartuStok::class,'bahan_produk_id','id');
    }

    public function penjualan()
    {
        return $this->belongsTo(StokKeluar::class,'penjualan_id','id');
    }
}
