<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $users = User::whereNot('role', 'pelanggan')
            ->get();

        return view('admin.home', compact('users'));
    }

    public function createUsers()
    {
        return view('admin.createuser');
    }

    public function storeUsers(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
            'alamat' => 'required|string|max:255'
        ]);

        $user = new User();
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->alamat = $request->alamat;

        $user->save();

        return redirect()->route('home')->with('success', 'Pembuatan akun berhasil.');
    }

    public function editUsers($id)
    {
        $user = User::findOrFail($id);

        return view('admin.edituser', compact('user'));
    }

    public function updateUsers(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required',
            'alamat' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->role = $request->role;
        $user->alamat = $request->alamat;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('home')->with('success', 'Update akun berhasil.');
    }

    public function deleteUsers(Request $request)
    {
        $userIds = explode(',', $request->input('user_ids'));
        User::whereIn('id', $userIds)->delete();

        return redirect()->back()->with('success', 'Hapus akun berhasil.');
    }

    public function kategori()
    {
        $kategoris = Kategori::All();

        return view('admin.kategori', compact('kategoris'));
    }

    public function createKategori()
    {
        return view('admin.createkategori');
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'status_obat' => 'required|string|max:255',
        ]);

        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->status_obat = $request->status_obat;

        $kategori->save();

        return redirect()->route('kategori')->with('success', 'Pembuatan kategori obat berhasil.');
    }

    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.editkategori', compact('kategori'));
    }

    public function updateKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'status_obat' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($request->kategori_id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->status_obat = $request->status_obat;

        $kategori->save();

        return redirect()->route('kategori')->with('success', 'Update kategori obat berhasil.');
    }

    public function deleteKategori(Request $request)
    {
        $kategoriIds = explode(',', $request->input('kategori_ids'));
        Kategori::whereIn('id', $kategoriIds)->delete();

        return redirect()->back()->with('success', 'Hapus kategori obat berhasil.');
    }

    public function obat()
    {
        $today = Carbon::now()->format('Y-m-d');

        $obats = Obat::with('kategori')
            ->whereDate('exp', '>', $today) // Hanya menampilkan data obat yang exp > hari ini
            ->get();

        return view('admin.obat', compact('obats'));
    }

    public function expObat()
    {
        $today = Carbon::now()->format('Y-m-d');

        $expObats = Obat::with('kategori')
            ->whereDate('exp', '<=', $today) // Menampilkan data obat yang exp <= hari ini
            ->get();

        return view('admin.expobat', compact('expObats'));
    }

    public function createObat()
    {
        $kategoris = Kategori::all();
        return view('admin.createobat', compact('kategoris'));
    }

    public function storeObat(Request $request)
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $request->validate([
            'kategori' => 'required|exists:kategoris,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_obat' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
            'exp' => 'required|date|after_or_equal:' . $tomorrow, // Hanya memperbolehkan tanggal besok dan seterusnya
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = $request->image->store('obat_images', 'public');
        }

        $obat = new Obat();
        $obat->id_kategori = $request->kategori;
        $obat->image = $imageName;
        $obat->nama_obat = $request->nama_obat;
        $obat->harga = $request->harga;
        $obat->keterangan = $request->keterangan;
        $obat->stok = $request->stok;
        $obat->exp = $request->exp;

        $obat->save();

        return redirect()->route('obat')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function editObat($id)
    {
        $obat = Obat::findOrFail($id);
        $kategoris = Kategori::all();

        return view('admin.editobat', compact('obat', 'kategoris'));
    }

    public function updateObat(Request $request)
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $request->validate([
            'kategori' => 'required|exists:kategoris,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_obat' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
            'exp' => 'required|date|after_or_equal:' . $tomorrow, // Hanya memperbolehkan tanggal besok dan seterusnya
        ]);

        $obat = Obat::findOrFail($request->obat_id);
        $obat->id_kategori = $request->kategori;
        $obat->nama_obat = $request->nama_obat;
        $obat->harga = $request->harga;
        $obat->keterangan = $request->keterangan;
        $obat->stok = $request->stok;
        $obat->exp = $request->exp;

        if ($request->hasFile('image')) {
            Storage::delete('public/obat_images/' . basename($obat->image));
            $imageName = $request->file('image')->store('obat_images', 'public');
            $obat->image = $imageName;
        }

        $obat->save();

        return redirect()->route('obat')->with('success', 'Obat berhasil diupdate.');
    }

    public function deleteObat(Request $request)
    {
        $obatIds = explode(',', $request->input('obat_ids'));
        $obatToDelete = Obat::whereIn('id', $obatIds)->get();

        foreach ($obatToDelete as $obat) {
            // Hapus file gambar terkait
            $imagePath = 'public/obat_images/' . basename($obat->image);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        Obat::whereIn('id', $obatIds)->delete();

        return redirect()->back()->with('success', 'Obat berhasil dihapus.');
    }
}
