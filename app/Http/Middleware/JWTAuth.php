<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;
use Illuminate\Support\Str;
use App\Models\User;

class JWTAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Ambil token dari header Authorization
        $jwt = $request->bearerToken();

        // Cek jika token tidak ada
        if (is_null($jwt)) {
            return response()->json(['error' => 'Akses Ditolak, token tidak ditemukan'], 401);
        }

        try {
            // Decode token
            $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($decoded->id);

            // Cek jika pengguna tidak ditemukan
            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
            }

            // Tambahkan pengguna ke request
            $request->merge(['auth' => $user]);

            // Cek peran pengguna dan izin akses
            $allowed = $this->checkAccess($user->role, $request->path());

            if ($allowed) {
                // Cek peran jika diberikan
                if ($role && $user->role !== $role) {
                    return response()->json(['error' => 'Anda tidak memiliki hak akses untuk peran ini'], 403);
                }

                return $next($request);
            } else {
                return response()->json(['error' => 'Anda tidak memiliki hak akses'], 403);
            }

        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token telah kadaluarsa'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        }
    }

    /**
     * Check access based on role and requested path
     *
     * @param  string  $role
     * @param  string  $path
     * @return bool
     */
    protected function checkAccess($role, $path)
    {
        $rolePermissions = [
            'Admin' => [],
            'Seller' => [
                'api/wishlists*', 
                'api/wishlists-item*', 
                'api/app-coupons*', 
                'api/carts*', 
                'api/cart-item*', 
                'api/refunds*',
                'api/payments*'
            ],
            'Customer' => [
                'api/main-categories*', 
                'api/sub-categories*', 
                'api/sizes*',
                'api/colors*',
                'api/shop-coupons*', 
                'api/promotions*', 
                'api/app-coupons*', 
                'api/transactions*', 
                'api/refunds*', 
            ],
        ];

        // Admin memiliki akses ke semua rute
        if ($role == 'Admin') {
            return true;
        }

        // Cek jika jalur tidak diizinkan untuk peran tertentu
        foreach ($rolePermissions[$role] as $pattern) {
            if (Str::is($pattern, $path)) {
                return false;
            }
        }

        return true;
    }
}
