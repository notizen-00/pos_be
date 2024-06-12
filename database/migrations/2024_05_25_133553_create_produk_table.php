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
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });
        Schema::create('bahan_produk',function (Blueprint $table){
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan');
            $table->integer('stok');
            $table->timestamps();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategori_produk');
            $table->double('harga', 8, 2);
            $table->boolean('favorit');
            $table->boolean('status');
            $table->string('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });


        Schema::create('resep_produk',function (Blueprint $table){
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onCascadeDelete()->onCascadeUpdate();
            $table->foreignId('bahan_produk_id')->constrained('bahan_produk')->onCascadeDelete()->onCascadeUpdate();
            $table->integer('quantity_resep');
            $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
        Schema::dropIfExists('bahan_produk');
        Schema::dropIfExists('resep_produk');
        Schema::dropIfExists('kategori_produk');
    }
};
