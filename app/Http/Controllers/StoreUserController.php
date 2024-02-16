<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StoreUserController extends Controller
{
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
            'alamat' => 'required|string|max:255'
        ]);

        $pelanggan = new Pelanggan();
        $pelanggan->nama_pelanggan = $request->nama_user;
        $pelanggan->username = $request->username;
        $pelanggan->password = Hash::make($request->password);
        $pelanggan->alamat = $request->alamat;

        $pelanggan->save();

        $idpelanggan = $pelanggan->id;

        $user = new User();
        $user->id_pelanggan = $idpelanggan;
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->alamat = $request->alamat;

        $user->save();

        Auth::login($user);

        return redirect()->route('order')->with('success', 'Berhasil login');
    }
}
