<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        try {
            $redirectUrl = Socialite::driver($provider)->redirect()->getTargetUrl();
            return response()->json(['url' => $redirectUrl], 200);
        } catch (\Exception $e) {
            Log::error('Error during redirect: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to redirect to provider.'], 500);
        }
    }

    public function callback($provider, Request $request)
    {
        try {
            $SocialUser = Socialite::driver($provider)->user();

            // Check if the email already exists and is using a different provider
            $existingUser = User::where('email', $SocialUser->getEmail())->first();
            if ($existingUser && $existingUser->provider !== $provider) {
                return response()->json(['error' => 'This email is registered with a different login method.'], 409);
            }

            // Find or create the user
            $user = User::where([
                'provider' => $provider,
                'provider_id' => $SocialUser->id
            ])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $SocialUser->getName(),
                    'email' => $SocialUser->getEmail(),
                    'username' => User::generateUserName($SocialUser->getNickname()),
                    'avatar' => $SocialUser->getAvatar(),
                    'role' => 'User',
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now(),
                ]);
            }

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
