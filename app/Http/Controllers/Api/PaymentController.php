<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        // Validasi bahwa pengguna telah terotentikasi sebagai pelanggan
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:debit_card,credit_card,e_wallet,bank_transfer,paypal,dana', // Add 'dana' here
            'payment_details' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Create the payment with user relationship
        $payment = Payment::create([
            'payment_method' => $validated['payment_method'],
            'payment_details' => $validated['payment_details'],
        ]);

        // Process payment using Midtrans if 'dana' payment method is selected
        if ($validated['payment_method'] === 'dana') {
            // Set Midtrans configuration
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // Create transaction details
            $transaction = [
                'transaction_details' => [
                    'order_id' => 'ORDER-ID-' . time(),
                    'gross_amount' => 10000, // Example: payment amount in IDR
                ],
                'customer_details' => [
                    'first_name' => 'Nama',
                    'email' => 'customer@example.com',
                ],
                'enabled_payments' => ['dana'],
            ];

            try {
                // Get Snap Token from Midtrans
                $snapToken = Snap::getSnapToken($transaction);

                // Return the Snap redirect URL to frontend
                return response()->json([
                    'message' => 'Pembayaran Dana berhasil dibuat',
                    'snap_token' => $snapToken,
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to create Dana payment: ' . $e->getMessage()], 500);
            }

        }
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // Simpan data pembayaran
        $validated = $validator->validated();
        $payment = $user->payments()->create($validated);

        return response()->json([
            'message' => 'Pembayaran berhasil dibuat',
            'data' => $payment
        ], 201);
    }

    public function read(Request $request)
    {
        $user = $request->user();
        // Ambil semua data payment
        $payments = Payment::all();

        return response()->json([
            'message' => 'Data semua pembayaran',
            'data' => $payments
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

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

    public function delete(Request $request, $id)
    {
        $user = $request->user();

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

