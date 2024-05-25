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
            // Other columns for detail_transaksi...
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
