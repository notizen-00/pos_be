<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\DetailTransaksiScope;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = 
    [
        'nomor_transaksi',
        'pelanggan_id',
        'nama_pelanggan',
        'meja',
        'author_id',
        'shift_id',
        'status',
        'total',
        'deskripsi',
        'total_tambahan',
        'pembayaran',
        'metode_pembayaran',
        'kembalian'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new DetailTransaksiScope);
    }

    public function user()
    {
        return $this->hasMany(User::class,'id','author_id');
    }
    public function detail_transaksi(){
        return $this->hasMany(DetailTransaksi::class);
    }
}
