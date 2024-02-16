<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function transaksi()
    {
        $transaksis = Penjualan::with('pembayaran')
            ->select('id_penjualan', DB::raw('SUM(total) as total_sum'), 'id_pembayaran', 'tanggal', 'id_user')
            ->whereNotNull('id_pembayaran')
            ->groupBy('id_penjualan', 'id_pembayaran', 'tanggal', 'id_user')
            ->get();

        return view('petugas.transaksi', compact('transaksis'));
    }

    public function selesai(Request $request)
    {
        $transaksiId = $request->input('transaksi_id');

        Penjualan::where('id_penjualan', $transaksiId)->update(['id_user' => auth()->user()->id]);

        return redirect()->back()->with('success', 'Pesanan sudah dibayar.');
    }

    public function cetakStruk($id_penjualan)
    {
        $struks = Penjualan::with(['obat', 'user', 'pelanggan', 'pembayaran'])
            ->where('id_penjualan', $id_penjualan)
            ->first();

        if ($struks) {
            $penjualans = Penjualan::with('obat')
                ->where('id_penjualan', $id_penjualan)->get();
            return view('petugas.struk', compact('struks', 'penjualans'));
        } else {
            return redirect()->back()->with('error', 'Penjualan tidak ditemukan');
        }
    }
}
