<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Resep extends Pivot
{
    use HasFactory;

    protected $table = 'resep_produk';

    protected $fillable = [
        'bahan_produk_id',
        'produk_id',
        'quantity_resep',
    ];

    public function bahan_produk()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

}
