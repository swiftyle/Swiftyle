<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function index()
    {
        $passwordResets = PasswordReset::all();
        return response()->json($passwordResets);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'otp_code' => 'nullable|string|max:255',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable|string|max:6',
            'email_token_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $passwordReset = PasswordReset::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'otp_code' => $request->otp_code,
            'otp_expiry' => $request->otp_expiry,
            'email_token' => $request->email_token,
            'email_token_expiry' => $request->email_token_expiry,
            'created_at' => now(),
        ]);

        return response()->json($passwordReset, 201);
    }

    public function show($phone, $email)
    {
        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        return response()->json($passwordReset);
    }

    public function update(Request $request, $phone, $email)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'otp_code' => 'nullable|string|max:255',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable|string|max:6',
            'email_token_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        $passwordReset->update($request->all());

        return response()->json($passwordReset);
    }

    public function destroy($phone, $email)
    {
        $passwordReset = PasswordReset::where('phone', $phone)->where('email', $email)->firstOrFail();
        $passwordReset->delete();

        return response()->json(null, 204);
    }
}
