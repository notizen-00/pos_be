<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';

    protected $fillable = 
    [
        'pembelian_id',
        'bahan_produk_id',
        'quantity',
        'harga_beli',
        'subtotal'
    ];

    public function pembelian()
    {
        return $this->belongsTo(StokMasuk::class,'pembelian_id','id');
    }

    public function stok_masuk()
    {
        return $this->belongsTo(KartuStok::class,'bahan_produk_id','id');
    }

    public static function getAverageHargaBeli(int $bahan_produk_id)
    {
        return self::where('bahan_produk_id', $bahan_produk_id)
            ->avg('harga_beli');
    }

}
