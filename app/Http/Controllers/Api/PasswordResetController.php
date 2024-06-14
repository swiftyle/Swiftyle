<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|unique:password_resets,phone',
            'email' => 'nullable|email|unique:password_resets,email',
            'otp_code' => 'nullable',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable',
            'email_token_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the password reset entry
        $passwordReset = PasswordReset::create([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'otp_code' => $request->input('otp_code'),
            'otp_expiry' => $request->input('otp_expiry'),
            'email_token' => $request->input('email_token'),
            'email_token_expiry' => $request->input('email_token_expiry'),
        ]);

        return response()->json([
            'message' => 'Password reset entry created successfully',
            'data' => $passwordReset
        ], 201);
    }

    public function readAll()
    {
        // Fetch all password reset entries
        $passwordResets = PasswordReset::all();

        return response()->json([
            'message' => 'Password reset entries fetched successfully',
            'data' => $passwordResets
        ], 200);
    }

    public function read($phone, $email)
    {
        // Fetch password reset entry by phone and email
        $passwordReset = PasswordReset::where('phone', $phone)
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Password reset entry not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Password reset entry fetched successfully',
            'data' => $passwordReset
        ], 200);
    }

    public function update(Request $request, $phone, $email)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'otp_code' => 'nullable',
            'otp_expiry' => 'nullable|date',
            'email_token' => 'nullable',
            'email_token_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the password reset entry
        $passwordReset = PasswordReset::where('phone', $phone)
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Password reset entry not found'])->setStatusCode(404);
        }

        // Update the password reset entry
        $passwordReset->otp_code = $request->input('otp_code', $passwordReset->otp_code);
        $passwordReset->otp_expiry = $request->input('otp_expiry', $passwordReset->otp_expiry);
        $passwordReset->email_token = $request->input('email_token', $passwordReset->email_token);
        $passwordReset->email_token_expiry = $request->input('email_token_expiry', $passwordReset->email_token_expiry);
        $passwordReset->save();

        return response()->json([
            'message' => 'Password reset entry updated successfully',
            'data' => $passwordReset
        ], 200);
    }

    public function delete($phone, $email)
    {
        // Find the password reset entry
        $passwordReset = PasswordReset::where('phone', $phone)
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Password reset entry not found'])->setStatusCode(404);
        }

        // Delete the password reset entry
        $passwordReset->delete();

        return response()->json(['message' => 'Password reset entry deleted successfully'], 200);
    }
}
