<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = [
        'nama',
        'harga',
        'status',
        'deskripsi',
        'kategori_id',
        'foto',
        'favorit'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class);
    }
}
