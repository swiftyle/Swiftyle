<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'checkout_id' => 'required|exists:checkouts,id',
            'shipping_address' => 'required|string|max:255',
            'courier_name' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipped_date' => 'required|date',
            'shipping_method' => 'required|in:car,ship,plane',
            'shipping_cost' => 'required|numeric',
            'shipping_status' => 'required|in:pending,shipped,delivered,cancelled',
            'payment_status' => 'required|in:cod,paid',
            'estimated_delivery_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the shipping record
        $shipping = Shipping::create([
            'checkout_id' => $request->input('checkout_id'),
            'shipping_address' => $request->input('shipping_address'),
            'courier_name' => $request->input('courier_name'),
            'tracking_number' => $request->input('tracking_number'),
            'shipped_date' => $request->input('shipped_date'),
            'shipping_method' => $request->input('shipping_method'),
            'shipping_cost' => $request->input('shipping_cost'),
            'shipping_status' => $request->input('shipping_status'),
            'payment_status' => $request->input('payment_status'),
            'estimated_delivery_date' => $request->input('estimated_delivery_date'),
        ]);

        return response()->json([
            'message' => 'Shipping record created successfully',
            'data' => $shipping
        ], 201);
    }

    public function read(Request $request)
    {
        $user = $request->user();

        // Fetch shipping records associated with user's checkouts
        $shippingRecords = Shipping::whereIn('checkout_id', function ($query) use ($user) {
            $query->select('id')
                ->from('checkouts')
                ->where('user_id', $user->id);
        })->get();

        return response()->json([
            'message' => 'Shipping records fetched successfully',
            'data' => $shippingRecords
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'checkout_id' => 'sometimes|required|exists:checkouts,id',
            'shipping_address' => 'sometimes|required|string|max:255',
            'courier_name' => 'sometimes|required|string|max:255',
            'tracking_number' => 'sometimes|required|string|max:255',
            'shipped_date' => 'sometimes|required|date',
            'shipping_method' => 'sometimes|required|in:car,ship,plane',
            'shipping_cost' => 'sometimes|required|numeric',
            'shipping_status' => 'sometimes|required|in:pending,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|required|in:cod,paid',
            'estimated_delivery_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the shipping record
        $shipping = Shipping::find($id);

        if ($shipping) {
            // Update the shipping record
            $shipping->update([
                'checkout_id' => $request->input('checkout_id', $shipping->checkout_id),
                'shipping_address' => $request->input('shipping_address', $shipping->shipping_address),
                'courier_name' => $request->input('courier_name', $shipping->courier_name),
                'tracking_number' => $request->input('tracking_number', $shipping->tracking_number),
                'shipped_date' => $request->input('shipped_date', $shipping->shipped_date),
                'shipping_method' => $request->input('shipping_method', $shipping->shipping_method),
                'shipping_cost' => $request->input('shipping_cost', $shipping->shipping_cost),
                'shipping_status' => $request->input('shipping_status', $shipping->shipping_status),
                'payment_status' => $request->input('payment_status', $shipping->payment_status),
                'estimated_delivery_date' => $request->input('estimated_delivery_date', $shipping->estimated_delivery_date),
            ]);

            return response()->json([
                'message' => 'Shipping record updated successfully',
                'data' => $shipping
            ], 200);
        }

        return response()->json([
            'message' => 'Shipping record not found'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        // Find the shipping record
        $shipping = Shipping::find($id);

        if ($shipping) {
            // Delete the shipping record
            $shipping->delete();

            return response()->json([
                'message' => 'Shipping record deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Shipping record not found'
        ], 404);
    }
}
