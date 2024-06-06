<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Mulai sesi
        (new StartSession)->handle($request, function ($request) use ($next) {
            // Enkripsi cookie
            (new EncryptCookies)->handle($request, function ($request) use ($next) {
                $session = app()->resolve('session');
            $session->flash('message', 'Selamat datang!'); // Menggunakan metode resolve() untuk mendapatkan instance kelas Session
                (new ShareErrorsFromSession)->handle($request, $next);
            });
        });

        return $next($request);
    }
}

