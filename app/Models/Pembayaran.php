<?php

namespace App\Models;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pembayaran',
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
