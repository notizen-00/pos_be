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
        
        Schema::create('bahan_produk',function (Blueprint $table){
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan');
            $table->integer('quantity_bahan');
            $table->integer('harga_bahan');
            $table->timestamps();
        });


        Schema::create('resep_produk',function (Blueprint $table){
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan_produk')->onCascadeDelete()->onCascadeUpdate();
            $table->integer('quantity_resep');
            $table->timestamps();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kategori_id');
            $table->string('harga');
            $table->boolean('favorit');
            $table->boolean('status');
            $table->foreignId('resep_produk_id')->constrained('resep_produk')->onCascadeDelete()->onCascadeUpdate();
            $table->string('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
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
