<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:debit_card,credit_card,e_wallet,bank_transfer,paypal',
            'payment_details' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the payment with user relationship
        $payment = $user->payments()->create($validated);

        return response()->json([
            'message' => 'Pembayaran berhasil dibuat',
            'data' => $payment
        ], 201);
    }

    public function read()
    {
        // Ambil semua data payment
        $payments = Payment::all();

        return response()->json([
            'message' => 'Data semua pembayaran',
            'data' => $payments
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Decode JWT token to get user data
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'payment_method' => 'sometimes|required|in:debit_card,credit_card,e_wallet,bank_transfer,paypal',
            'payment_details' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data payment yang akan diupdate
        $payment = $user->payments()->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Update data payment sesuai dengan data yang divalidasi
        $validated = $validator->validated();
        $payment->update($validated);

        return response()->json([
            'message' => 'Pembayaran berhasil diupdate',
            'data' => $payment
        ], 200);
    }

    public function delete($id)
    {
        // Decode JWT token to get user data
        $data = JWT::decode(request()->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Cari payment yang akan dihapus
        $payment = $user->payments()->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Hapus payment
        $payment->delete();

        return response()->json([
            'message' => 'Pembayaran berhasil dihapus'
        ], 200);
    }
}

