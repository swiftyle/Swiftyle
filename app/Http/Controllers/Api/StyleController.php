<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        $userId = $user->id;
        $userEmail = $user->email;

        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['modified_by'] = $userEmail;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('styleImage');
            $file->move($filePath, $fileName);
            $validated['image'] = 'public/styleImage/' . $fileName;
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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data style yang akan diupdate
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Style tidak ditemukan'], 404);
        }

        // Update data style sesuai dengan data yang divalidasi
        $validated = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if (!is_null($style->image) && File::exists(public_path($style->image))) {
                File::delete(public_path($style->image));
            }

            // Store new image
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('styleImage');
            $file->move($filePath, $fileName);
            $validated['image'] = 'styleImage/' . $fileName;
        }

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

        // Delete old image if it exists
        if (!is_null($style->image) && File::exists(public_path($style->image))) {
            File::delete(public_path($style->image));
        }

        // Hapus style
        $style->delete();

        return response()->json([
            'message' => 'Style berhasil dihapus'
        ], 200);
    }
}
