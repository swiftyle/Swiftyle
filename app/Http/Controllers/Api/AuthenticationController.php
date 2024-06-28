<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $validated = $validator->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer', // Set a default role, adjust as necessary
            'phone_verified' => 'No',
            'gender' => 'Other',
            'status' => 'Active',
        ]);

        // Generate JWT token
        $token = $this->generateToken($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $credentials = $validator->validated();

        try {
            if (!Auth::attempt($credentials)) {
                return response()->json(["error" => "Credentials not found"], 422);
            }

            $user = Auth::user();

            // Generate JWT token
            $token = $this->generateToken($user);

            // Create or update cart for Customer role
            if (strtolower($user->role) === 'customer') {
                $this->createOrUpdateCart($user);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'bearer' => $token
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Authentication error: ' . $e->getMessage()], 500);
        }
    }

    protected function createOrUpdateCart($user)
    {
        // Find existing cart or create new if not exists
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            Cart::create([
                'user_id' => $user->id,
                'app_coupon_id' => null,
                'total_discount' => 0,
                'total_price' => 0,
            ]);
        }
    }

    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid refresh token'], 422);
        }

        $refreshToken = $request->refresh_token;

        try {
            // Decode refresh token
            $decoded = JWT::decode($refreshToken, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            // Validate the token
            if ($decoded->exp < time()) {
                return response()->json(['error' => 'Refresh token expired'], 401);
            }

            // Find the user based on token data
            $user = User::find($decoded->sub);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Generate new access token
            $accessToken = $this->generateToken($user);

            return response()->json([
                'access_token' => $accessToken,
                'token_type' => 'bearer',
                'expires_in' => Carbon::now()->addHours(1)->timestamp // Adjust as needed
            ], 200);

        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Refresh token expired'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Invalidate the token and log out the user
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    protected function generateToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHours(1)->timestamp // Access token expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }
}
