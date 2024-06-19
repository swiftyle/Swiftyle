<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StyleController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        // Check if the authenticated user is an admin
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $filePath;
        }
        // Create the style without associating it with the user
        $style = Style::create($validated);

        return response()->json([
            'message' => 'Style berhasil dibuat',
            'data' => $style
        ], 201);
    }

    public function read()
    {
        // Ambil semua data style
        $styles = Style::all();

        return response()->json([
            'message' => 'Data semua style',
            'data' => $styles
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        // Check if the authenticated user is an admin
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'somtimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data style yang akan diupdate
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Style tidak ditemukan'], 404);
        }

        if ($style) {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if (!is_null($style->image)) {
                    Storage::disk('public')->delete($style->image);
                    // Store new image
                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;
                } else {

                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;

                }
            }
        }
        // Update data style sesuai dengan data yang divalidasi
        $validated = $validator->validated();
        $style->update($validated);

        return response()->json([
            'message' => 'Style berhasil diupdate',
            'data' => $style
        ], 200);
    }


    public function delete(Request $request, $id)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        // Check if the authenticated user is an admin
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Cari style yang akan dihapus
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Style tidak ditemukan'], 404);
        }

        // Hapus style
        $style->delete();

        return response()->json([
            'message' => 'Style berhasil dihapus'
        ], 200);
    }

}

