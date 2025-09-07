<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $roles = $user->getRoleNames();

        if (in_array('super-admin', $roles)) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array('siswa', $roles)) {
            return redirect()->route('siswa.dashboard');
        }

        if (in_array('instruktur dudi', $roles)) {
            return redirect()->route('instruktur.dashboard');
        }

        if (in_array('guru-pembimbing', $roles)) {
            return redirect()->route('guru.dashboard');
        }

        if (in_array('pokja', $roles)) {
            return redirect()->route('pokja.dashboard');
        }

        if (collect($roles)->contains(fn($r) => str_starts_with($r, 'kakom-'))) {
            return redirect()->route('kakom.dashboard');
        }

        return $next($request);
    }
}
