<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = 
    [
        'transaksi_id',
        'nomor_penjualan',
        'total_penjualan',
        'author_id',
        'deskripsi'
    ];

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class,'penjualan_id','id');
    }
}
