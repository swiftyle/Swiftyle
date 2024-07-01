<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function read(Request $request)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Memeriksa apakah role user adalah Admin
        if ($user->role == 'Admin') {
            // Admin dapat melihat semua user
            $user = User::all();
            return response()->json([
                'msg' => 'Data User Keseluruhan',
                'data' => $user
            ], 200);
        } else {
            // Pengguna non-admin hanya dapat melihat data mereka sendiri
            $user = User::where('id', $user->id)->get();
            return response()->json([
                'msg' => 'Data Pengguna',
                'data' => $user
            ], 200);
        }
    }

    public function readById($id)
    {
        // Ambil data user berdasarkan id
        $user = User::find($id);

        // $user = DB::select("SELECT * FROM users WHERE id = $id");

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Data user',
            'data' => $user
        ], 200);
    }
    public function readByUsername($username)
    {
        // Ambil data user berdasarkan username
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Data user',
            'data' => $user
        ], 200);
    }
    public function readByEmail($email)
    {
        // Ambil data user berdasarkan email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Data user',
            'data' => $user
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Dapatkan user yang terautentikasi
        $authenticatedUser = auth()->user();

        // Periksa apakah user yang terautentikasi adalah user yang akan diupdate atau admin
        if ($authenticatedUser->id != $id && $authenticatedUser->role != 'Admin') {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengupdate data user ini'], 403);
        }

        // Validasi data masukan
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'phone_number' => 'sometimes|string|max:20|unique:users,phone_number,' . $id,
            'gender' => 'sometimes|in:Male,Female,Other',
            'role' => 'sometimes|in:Admin,Customer,Seller',
            'avatar' => 'sometimes|string',
            'status' => 'sometimes|in:Active,Inactive',
            'provider' => 'sometimes|string',
            'provider_id' => 'sometimes|string',
            'provider_token' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // Dapatkan data user yang akan diupdate
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Update data user sesuai dengan data yang divalidasi
        $userData = $validator->validated();

        // Jika password diberikan, hash password sebelum disimpan
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('userAvatar');
            $file->move($filePath, $fileName);
            $userData['avatar'] = 'public/userAvatar/' . $fileName;
        }
        $user->update($userData);

        return response()->json([
            'message' => 'User berhasil diupdate',
            'data' => $user
        ], 200);
    }

    public function delete($id)
    {
        // Dapatkan user yang terautentikasi
        $authenticatedUser = auth()->user();

        // Cari user yang akan dihapus
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Periksa apakah user yang terautentikasi adalah user yang ingin dihapus
        if ($authenticatedUser->id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus akun ini'], 403);
        }

        // Hapus user
        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ], 200);
    }


    public function restore($id)
    {
        // Dapatkan user yang terautentikasi
        $authenticatedUser = auth()->user();

        // Periksa apakah user yang terautentikasi adalah admin
        if ($authenticatedUser->role !== 'admin') {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengembalikan akun ini'], 403);
        }

        // Cari user yang akan dikembalikan
        $user = User::onlyTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Kembalikan user
        $user->restore();

        return response()->json([
            'message' => 'User berhasil dikembalikan'
        ], 200);
    }

}

