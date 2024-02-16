<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role == 'pelanggan') {
            return '/order';
        } elseif ($user->role == 'petugas') {
            return '/transaksi';
        } else {
            return '/user';
        }

        return '/user';
    }
}
