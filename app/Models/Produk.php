<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\KategoriDetailScope;
use Carbon\Carbon;

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
    protected static function booted(): void
    {
        static::addGlobalScope(new kategoriDetailScope);
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class);
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
