<?php

namespace App\Http\Controllers\Api;

use App\Events\ComplaintSubmitted;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Ensure user is a Customer
        if ($user->role !== 'Customer') {
            return response()->json(['message' => 'Only customers can create complaints'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the complaint
        $complaint = Complaint::create([
            'user_id' => $request->input('user_id'),
            'order_id' => $request->input('order_id'),
            'description' => $request->input('description'),
        ]);

        event(new ComplaintSubmitted($complaint));

        return response()->json([
            'message' => 'Complaint created successfully',
            'data' => $complaint
        ], 201);
    }

    public function readAll(Request $request)
    {
        $user = $request->user();

        // Ensure user is an Admin
        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Only admins can read all complaints'], 403);
        }

        // Fetch all complaints
        $complaints = Complaint::all();

        return response()->json([
            'message' => 'Complaints fetched successfully',
            'data' => $complaints
        ], 200);
    }

    public function read(Request $request, $id)
    {
        $user = $request->user();

        // Fetch complaint by ID
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json(['message' => 'Complaint not found'])->setStatusCode(404);
        }

        // Check if the user is either Customer or Seller related to the complaint
        if ($user->role !== 'Customer' && !$this->isSellerRelatedToComplaint($user->id, $complaint)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Return the complaint
        return response()->json([
            'message' => 'Complaint fetched successfully',
            'data' => $complaint
        ], 200);
    }

    private function isSellerRelatedToComplaint($userId, $complaint)
    {
        // Implement logic to check if the user (seller) is related to the complaint
        // You mentioned that the seller is related via shop_id in products sold through the order process

        // Get the order related to the complaint
        $order = $complaint->order;

        if ($order) {
            // Get products associated with the order
            $products = $order->products;

            // Loop through each product to find if the seller (user) is related
            foreach ($products as $product) {
                $shop = $product->shop;

                // Check if the shop's seller_id matches the user's id
                if ($shop && $shop->seller_id === $userId) {
                    return true; // User is related as a seller
                }
            }
        }

        return false; // User is not related as a seller
    }

    // public function update(Request $request, $id)
    // {
    //     // Validate incoming request
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'exists:users,id',
    //         'order_id' => 'exists:orders,id',
    //         'status' => 'in:pending,resolved,rejected',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->messages())->setStatusCode(422);
    //     }

    //     // Find the complaint
    //     $complaint = Complaint::find($id);

    //     if (!$complaint) {
    //         return response()->json(['message' => 'Complaint not found'])->setStatusCode(404);
    //     }

    //     // Update the complaint
    //     $complaint->user_id = $request->input('user_id', $complaint->user_id);
    //     $complaint->order_id = $request->input('order_id', $complaint->order_id);
    //     $complaint->description = $request->input('description', $complaint->description);
    //     $complaint->status = $request->input('status', $complaint->status);
    //     $complaint->save();

    //     return response()->json([
    //         'message' => 'Complaint updated successfully',
    //         'data' => $complaint
    //     ], 200);
    // }

    // public function delete($id)
    // {
    //     // Find the complaint
    //     $complaint = Complaint::find($id);

    //     if (!$complaint) {
    //         return response()->json(['message' => 'Complaint not found'])->setStatusCode(404);
    //     }

    //     // Delete the complaint
    //     $complaint->delete();

    //     return response()->json(['message' => 'Complaint deleted successfully'], 200);
    // }
}
