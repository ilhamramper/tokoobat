<?php

namespace App\Models;

use App\Models\Obat;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penjualan',
        'id_pelanggan',
        'id_pembayaran',
        'kode_obat',
        'id_user',
        'tanggal',
        'jumlah',
        'total'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'kode_obat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran');
    }
}
