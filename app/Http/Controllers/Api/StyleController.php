<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Style;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StyleController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the style with user relationship
        $style = $user->styles()->create($validated);

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
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data style yang akan diupdate
        $style = $user->styles()->find($id);

        if (!$style) {
            return response()->json(['message' => 'Style tidak ditemukan'], 404);
        }

        // Update data style sesuai dengan data yang divalidasi
        $validated = $validator->validated();
        $style->update($validated);

        return response()->json([
            'message' => 'Style berhasil diupdate',
            'data' => $style
        ], 200);
    }

    public function delete($id)
    {
        // Decode JWT token to get user data
        $data = JWT::decode(request()->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Cari style yang akan dihapus
        $style = $user->styles()->find($id);

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

