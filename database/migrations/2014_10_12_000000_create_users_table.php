<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pelanggan')->nullable();
            $table->string('nama_user');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'petugas', 'pelanggan']);
            $table->text('alamat');
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'nama_user' => 'Ilham Admin',
                'username' => 'admin1',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'alamat' => 'Jalan Jalan',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
