<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan Auth::check() agar lebih stabil di Laravel terbaru
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
