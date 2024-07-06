<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        $parameters = [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => 'http://127.0.0.1:8000/api/auth/google/callback',
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'offline',
            'include_granted_scopes' => 'true',
            'state' => 'state_parameter_passthrough_value'
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($parameters);
        return response()->json(['redirect_url' => $authUrl]);
    }

    public function callback($provider, Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        try {
            $client = new Client();
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'code' => $code,
                    'client_id' => env('GOOGLE_CLIENT_ID'),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                    'redirect_uri' => 'http://127.0.0.1:8000/api/auth/google/callback',
                    'grant_type' => 'authorization_code',
                ],
            ]);

            $tokenData = json_decode($response->getBody(), true);
            $accessToken = $tokenData['access_token'];

            // Use the access token to get user information
            $SocialUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);

            // Check if the email already exists and is using a different provider
            $existingUser = User::where('email', $SocialUser->getEmail())->first();
            if ($existingUser && $existingUser->provider !== $provider) {
                return response()->json(['error' => 'This email is registered with a different login method.'], 409);
            }

            // Find or create the user
            $user = User::firstOrCreate(
                ['provider' => $provider, 'provider_id' => $SocialUser->getId()],
                [
                    'name' => $SocialUser->getName(),
                    'email' => $SocialUser->getEmail(),
                    'username' => User::generateUserName($SocialUser->getNickname()),
                    'avatar' => $SocialUser->getAvatar(),
                    'role' => 'Customer',
                    'provider_token' => $SocialUser->token,
                    'email_verified' => 'Yes',
                    'email_verified_at' => now(),
                    'status' => 'Active'
                ]
            );

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Return user details with token
            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                ],
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error during callback: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication failed.'], 500);
        }
    }
}
