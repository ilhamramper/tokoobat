<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pengecekan apakah pengguna sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Pengecekan level pengguna
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        }

        // Jika tidak sesuai, redirect dengan pesan error
        return redirect()->back()->with('error', 'Unauthorized User');
    }
}
