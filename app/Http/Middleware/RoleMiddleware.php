<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Pemakaian di route: middleware('role:admin')
     * Boleh lebih dari satu peran: middleware('role:admin,editor')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // sudah login, tetapi perannya tidak termasuk yang diizinkan -> 403
        if (! $user || ! in_array($user->role, $roles, true)) {
            $pesan = 'Akses ditolak. Anda tidak memiliki izin untuk aksi ini.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $pesan], 403);  // API
            }
            abort(403, $pesan);                                       // halaman web
        }

        return $next($request);
    }
}