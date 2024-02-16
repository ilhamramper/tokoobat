<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_pembayaran', ['Cash', 'Transfer', 'Kredit']);
            $table->timestamps();
        });

        DB::table('pembayarans')->insert([
            [
                'nama_pembayaran' => 'Cash',
            ],
            [
                'nama_pembayaran' => 'Transfer',
            ],
            [
                'nama_pembayaran' => 'Kredit',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
