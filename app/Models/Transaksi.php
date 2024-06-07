<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\DetailTransaksiScope;
use Carbon\Carbon;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            $transaksi->nomor_transaksi = static::generate_nomor_transaksi();
        });
    }

    public static function generate_nomor_transaksi()
    {
        $latestTransaksi = Transaksi::latest()->first();
        $latestNomorTransaksi = $latestTransaksi ? $latestTransaksi->id : 0;
        $nextNomorTransaksi = $latestNomorTransaksi + 1;
        $tanggalSekarang = now()->format('Ymd');
        return "TRX/" . str_pad($nextNomorTransaksi, 3, '0', STR_PAD_LEFT) . "/$tanggalSekarang";
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    // Mutator for created_at
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Accessor for updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    // Mutator for updated_at
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    
    public function user()
    {
        return $this->hasMany(User::class,'id','author_id');
    }
    public function detail_transaksi(){
        return $this->hasMany(DetailTransaksi::class);
    }
}
