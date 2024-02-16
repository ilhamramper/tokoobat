<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function order()
    {
        $today = Carbon::now()->format('Y-m-d');
        $id_pelanggan = auth()->user()->id_pelanggan;

        $kategoris = Kategori::all();
        $pembayarans = Pembayaran::all();
        $keranjang = Penjualan::with('obat')
            ->where('id_pelanggan', $id_pelanggan)
            ->whereNull('id_pembayaran')
            ->count();

        $obats = Obat::with('kategori')
            ->where('stok', '!=', 0)
            ->whereDate('exp', '>', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.order', compact('kategoris', 'obats', 'pembayarans', 'keranjang'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $tanggal = now();
        $id_pelanggan = auth()->user()->id_pelanggan;

        $latestOrder = Penjualan::latest('created_at')->first();
        $maxId = Penjualan::max('id_penjualan');
        $id_penjualan_max = $maxId ? $maxId + 1 : 1;

        if (!$latestOrder) {
            // Jika belum ada data di tabel penjualan, set id_penjualan menjadi 1
            $id_penjualan = 1;
        } else {
            // Jika ada data di tabel penjualan
            $latestOrderSameUser = Penjualan::where('id_pelanggan', $id_pelanggan)->latest('created_at')->first();

            if (!$latestOrderSameUser) {
                // Jika belum ada data dengan id_pelanggan yang sama, ambil id_penjualan yang paling baru dari seluruh data
                $id_penjualan = $latestOrder->id_penjualan + 1;
            } else {
                // Jika ada data dengan id_pelanggan yang sama
                if ($latestOrderSameUser->id_user === null && $latestOrderSameUser->id_pembayaran === null) {
                    // Jika id_user pada data paling baru dengan id_pelanggan yang sama adalah null, gunakan id_penjualan yang sama
                    $id_penjualan = $latestOrderSameUser->id_penjualan;
                } else {
                    $id_penjualan = $id_penjualan_max;
                }
            }
        }

        $order = new Penjualan();
        $order->id_penjualan = $id_penjualan;
        $order->jumlah = $request->input('qty');
        $order->tanggal = $tanggal;
        $order->id_pelanggan = $id_pelanggan;
        $order->kode_obat = $request->input('idobat');
        $order->total = $request->input('totalharga');

        $order->save();

        return redirect()->back()->with('success', 'Obat berhasil ditambah ke keranjang.');
    }

    public function keranjang()
    {
        $id_pelanggan = auth()->user()->id_pelanggan;
        $keranjangs = Penjualan::with('obat')
            ->where('id_pelanggan', $id_pelanggan)
            ->whereNull('id_pembayaran')
            ->get();
        $pembayarans = Pembayaran::all();

        return view('user.keranjang', compact('keranjangs', 'pembayarans'));
    }

    public function updateOrder($id, Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $keranjang = Penjualan::findOrFail($id);
        $keranjang->jumlah = $request->qty;
        $keranjang->total = $request->qty * $keranjang->obat->harga; // Sesuaikan dengan logika perhitungan total yang diinginkan
        $keranjang->save();

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function deleteOrder($id)
    {
        $keranjang = Penjualan::findOrFail($id);
        $keranjang->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function bayarPesanan(Request $request)
    {
        $idPelanggan = auth()->user()->id_pelanggan;
        $idPenjualan = Penjualan::where('id_pelanggan', $idPelanggan)
            ->whereNull('id_pembayaran')
            ->orderByDesc('id_penjualan')
            ->value('id_penjualan');

        if ($idPenjualan) {
            $idPembayaran = $request->input('id_pembayaran');

            // Update id_pembayaran for the specified order
            Penjualan::where('id_penjualan', $idPenjualan)
                ->whereNull('id_pembayaran')
                ->update(['id_pembayaran' => $idPembayaran]);

            // Reduce stock in the Obat table
            $items = Penjualan::where('id_penjualan', $idPenjualan)->get();

            foreach ($items as $item) {
                $obat = Obat::find($item->kode_obat);
                if ($obat) {
                    $obat->stok -= $item->jumlah;
                    $obat->save();
                }
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function riwayatOrder()
    {
        $id = auth()->user()->id_pelanggan;

        $riwayatOrders = Penjualan::with('pembayaran')
            ->select('id_penjualan', DB::raw('SUM(total) as total_sum'), 'id_pembayaran', 'tanggal', 'id_user')
            ->where('id_pelanggan', $id)
            ->whereNotNull('id_pembayaran')
            ->groupBy('id_penjualan', 'id_pembayaran', 'tanggal', 'id_user')
            ->get();

        return view('user.riwayatorder', compact('riwayatOrders'));
    }
}
