<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';

    protected $fillable = [
        'nama_kategori'
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Mutator for created_at
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Accessor for updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Mutator for updated_at
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
