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
        Schema::create('produk', function (Blueprint $table) {
            $table->Increments('id_produk');
            $table->string('id_kategori');
            $table->string('nama_produk')->unique();
            $table->string('brand');
            $table->string('harga_beli');
            $table->string('harga_jual');
            $table->tinyInteger('diskon')->default(0);
            $table->string('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
