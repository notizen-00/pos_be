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
        Schema::create('operasional', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe',['pemasukan','pengeluaran']);
            $table->integer('shift_id')->nullable();
            $table->integer('jumlah');
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operasional');
    }
};
