<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreferenceController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'style_ids' => 'required|array',
            'style_ids.*' => 'required|exists:styles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $createdPreferences = [];
        $existingPreferences = [];
        $errors = [];

        try {
            DB::beginTransaction();

            foreach ($validated['style_ids'] as $styleId) {
                // Check if the user already has a preference with the same style_id
                if ($user->preferences()->where('style_id', $styleId)->exists()) {
                    $existingPreferences[] = $styleId;
                    continue;
                }

                // Create new preference
                $preference = Preference::firstOrCreate([
                    'user_id' => $user->id,
                    'style_id' => $styleId
                ]);

                $createdPreferences[] = $preference;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process preferences', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Preferences processed',
            'created' => $createdPreferences,
            'existing' => $existingPreferences,
            'errors' => $errors,
        ], 201);
    }

    public function read(Request $request)
    {
        $user = $request->user();

        // Retrieve preferences associated with the authenticated user
        $preferences = $user->preferences()->get();

        return response()->json([
            'message' => 'User preferences retrieved successfully',
            'data' => $preferences
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'style_id' => 'sometimes|required|exists:styles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $preference = $user->preferences()->find($id);

        if (!$preference) {
            return response()->json(['message' => 'Preference not found'], 404);
        }

        // Check if the user has access to update this preference
        if ($preference->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this preference'], 403);
        }

        $validated = $validator->validated();

        try {
            $preference->update($validated);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update preference', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Preference updated successfully',
            'data' => $preference
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        $preference = $user->preferences()->find($id);

        if (!$preference) {
            return response()->json(['message' => 'Preference not found'], 404);
        }

        // Check if the user has access to delete this preference
        if ($preference->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to delete this preference'], 403);
        }

        try {
            $preference->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete preference', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Preference deleted successfully'], 200);
    }

    public function saveUserPreferences(Request $request, $orderId)
    {
        $user = $request->user();

        // Find the order by orderId
        $order = Order::findOrFail($orderId);

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to access this order'], 403);
        }

        // Get the product from the order
        $product = $order->shipping->checkout->cart->cartItems()->first()->product;

        if (!$product) {
            return response()->json(['message' => 'Product not found in the order'], 404);
        }

        // Get the style_id associated with the product
        $styleId = $product->styles()->first()->id;

        // Save the preference (assuming user_preferences is a pivot table)
        $user->preferences()->syncWithoutDetaching([$styleId]);

        return response()->json(['message' => 'User preferences updated successfully'], 200);
    }
}

