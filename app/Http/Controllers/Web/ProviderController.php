<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        // try {
        $SocialUser = Socialite::driver($provider)->user();
        // if (User::where('email', $SocialUser->getEmail())->exists()) {
        //     return redirect('/login')->withErrors(['email' => 'This email uses different method to login']);
        // }
        $user = User::where([
            'provider' => $provider,
            'provider_id' => $SocialUser->id
        ])->first();
        if (!$user) {
            $user = User::create(
                [
                    'name' => $SocialUser->getName(),
                    'email' => $SocialUser->getEmail(),
                    'username' => User::generateUserName($SocialUser->nickname),
                    'avatar' => $SocialUser->getAvatar(),
                    'role' => 'Admin',
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now(),

                ]

            );
        }

        Auth::login($user);
        
        $user = Auth::user();

        // Store user details in the session
        Session::put('name', $user->name);
        Session::put('username', $user->username);
        Session::put('email', $user->email);
        Session::put('phone', $user->phone);
        Session::put('gender', $user->gender);
        Session::put('role', $user->role);
        Session::put('avatar', $user->avatar);

        return redirect()->intended('/dashboard');
    }
}
