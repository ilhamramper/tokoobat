<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'id_kategori',
        'nama_obat',
        'harga',
        'keterangan',
        'stok',
        'exp',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_penjualan');
    }
}
