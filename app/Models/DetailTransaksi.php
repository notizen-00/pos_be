<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Scopes\DetailTransaksiWithProdukPivotScope;
class DetailTransaksi extends Pivot
{
    use HasFactory;
    
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'produk_id',
        'quantity',
        'transaksi_id',
        'subtotal'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new DetailTransaksiWithProdukPivotScope);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
