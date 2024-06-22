<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function index()
    {
        return view('admin.authentication.forget-password');
    }

    public function create()
    {
        return view('password_resets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'otp_code' => 'nullable|string|max:255',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable|string|max:6',
            'email_token_expiry' => 'nullable|date',
        ]);

        PasswordReset::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'otp_code' => $request->otp_code,
            'otp_expiry' => $request->otp_expiry,
            'email_token' => $request->email_token,
            'email_token_expiry' => $request->email_token_expiry,
            'created_at' => now(),
        ]);

        return redirect()->route('password_resets.index');
    }

    public function show($phone, $email)
    {
        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        return view('password_resets.show', compact('passwordReset'));
    }

    public function edit($phone, $email)
    {
        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        return view('password_resets.edit', compact('passwordReset'));
    }

    public function update(Request $request, $phone, $email)
    {
        $request->validate([
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'otp_code' => 'nullable|string|max:255',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable|string|max:6',
            'email_token_expiry' => 'nullable|date',
        ]);

        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        $passwordReset->update($request->all());

        return redirect()->route('password_resets.index');
    }

    public function destroy($phone, $email)
    {
        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        $passwordReset->delete();
        return redirect()->route('password_resets.index');
    }
}
