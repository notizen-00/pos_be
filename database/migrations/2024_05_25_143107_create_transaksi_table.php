<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi');
            $table->integer('pelanggan_id')->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->integer('meja')->nullable();
            $table->integer('author_id');
            $table->integer('shift_id');
            $table->enum('status',['open','closed','cancel']);
            $table->integer('total');
            $table->string('deskripsi')->nullable();
            $table->integer('total_tambahan')->nullable();
            $table->integer('pembayaran')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->integer('kembalian')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->timestamps();
        });

        Schema::create('penjualan',function(Blueprint $table){
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_penjualan');
            $table->double('total_penjualan',8,2);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('deskripsi')->nullable();
            $table->dateTime('tanggal_penjualan');
            $table->timestamps(); 
        });

        Schema::create('detail_penjualan',function(Blueprint $table){
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('bahan_produk_id')->constrained('bahan_produk')->onDelete('cascade');
            $table->integer('quantity'); 
            $table->double('harga_asset',8,2);
            $table->double('subtotal',8,2);
            $table->timestamps();
        });

        Schema::create('pembelian',function(Blueprint $table){
            $table->id();
            $table->string('nomor_pembelian');
            $table->double('total_pembelian',8,2);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('deskripsi')->nullable();
            $table->dateTime('tanggal_pembelian');
            $table->timestamps();
        });

        Schema::create('detail_pembelian',function(Blueprint $table){
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->foreignId('bahan_produk_id')->constrained('bahan_produk')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('harga_beli',8,2);
            $table->double('subtotal',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
    }
};
