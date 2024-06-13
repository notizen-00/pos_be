<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = 
    [
        'nomor_pembelian',
        'total_pembelian',
        'tanggal_pembelian',
        'author_id',
        'deskripsi'
    ];

    public function detail_pembelian()
    {
        return $this->hasMany(DetailPembelian::class,'pembelian_id','id');
    }

    public function author()
    {
        return $this->hasOne(User::class,'id','author_id');
    }

}
