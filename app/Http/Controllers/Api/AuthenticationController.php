<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Twilio\Rest\Client;

class AuthenticationController extends Controller
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

     public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => 'sometimes|string',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $validated = $validator->validate();

        // Kirim OTP ke email
        $otp = rand(100000, 999999);
        $this->sendEmailOTP($validated['email'], $otp);

        // Simpan data pengguna sementara dengan status "Pending"
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number']?? null,
            'avatar' => $validated['avatar'] ?? 'https://swiftyleshop.com/assets/images/dashboard/1.png',
            'role' => 'Customer',
            'gender' => 'Other',
            'phone_verified' => 'No',
            'email_verified' => 'No',
            'status' => 'Pending',
            'email_otp' => $otp,
        ]);

        return response()->json([
            'message' => 'OTP sent to your email. Please verify.',
            'user_id' => $user->id
        ], 201);
    }

    protected function sendEmailOTP($email, $otp)
    {
        // Kirim email menggunakan Laravel mail
        Mail::to($email)->send(new SendOtpMail($otp));
    }

    public function confirmEmailOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $validated = $validator->validate();
        $user = User::find($validated['user_id']);

        if ($user->email_otp == $validated['otp']) {
            $user->email_verified = 'Yes';
            $user->email_otp = null;
            $user->email_verified_at = Carbon::now();
            $user->status = 'Active';
            $user->save();

            // Generate JWT token
            $token = $this->generateToken($user);

            return response()->json([
                'message' => 'Email verified successfully.',
                'user_id' => $user->id,
                'token' => $token
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }
    }


    public function inputPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'phone_number' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $validated = $validator->validate();
        $user = User::find($validated['user_id']);

        // Kirim OTP ke nomor telepon
        $otp = rand(100000, 999999);
        $this->sendPhoneOTP($validated['phone_number'], $otp);

        // Update nomor telepon dan simpan OTP
        $user->phone_number = $validated['phone_number'];
        $user->phone_otp = $otp;
        $user->save();

        return response()->json([
            'message' => 'OTP sent to your phone. Please verify.',
            'user_id' => $user->id
        ], 200);
    }

    protected function sendPhoneOTP($phoneNumber, $otp)
    {
        $message = "Your verification code is: $otp";

        try {
            $this->twilio->messages->create("whatsapp:$phoneNumber", [
                'from' => env('TWILIO_WHATSAPP_SANDBOX_NUMBER'),
                'body' => $message
            ]);

            return response()->json([
                'message' => 'OTP sent to WhatsApp. Please verify.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmPhoneOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $validated = $validator->validate();
        $user = User::find($validated['user_id']);

        if ($user->phone_otp == $validated['otp']) {
            $user->phone_verified = 'Yes';
            $user->status = 'Active';
            $user->phone_otp = null; // Hapus OTP setelah verifikasi
            $user->phone_verified_at = Carbon::now();
            $user->save();

            // Generate JWT token
            $token = $this->generateToken($user);

            return response()->json([
                'message' => 'Phone number verified successfully',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }
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
                $this->createOrUpdateWishlist($user);
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

    protected function createOrUpdateWishlist($user)
    {
        // Find existing wishlist or create new if not exists
        $wishlist = Wishlist::where('user_id', $user->id)->first();

        if (!$wishlist) {
            Wishlist::create([
                'user_id' => $user->id,
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
            'exp' => Carbon::now()->addHours(24)->timestamp // Access token expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }
}
