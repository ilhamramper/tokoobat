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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_penjualan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_pembayaran')->nullable();
            $table->unsignedBigInteger('kode_obat');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->integer('total');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id')->on('pelanggans');
            $table->foreign('id_pembayaran')->references('id')->on('pembayarans');
            $table->foreign('kode_obat')->references('id')->on('obats');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
