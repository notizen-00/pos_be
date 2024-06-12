<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahan_produk';

    protected $fillable =
    [
        'nama_bahan',
        'satuan',
        'stok'
    ];

     public function produks()
    {
        return $this->belongsToMany(Produk::class, 'resep_produk','bahan_produk_id','produk_id')
                    ->withPivot('quantity_resep')
                    ->using(Resep::class);
    }
}
