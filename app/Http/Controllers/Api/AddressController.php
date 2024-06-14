<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function create(Request $request)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Memastikan bahwa pengguna bukan Seller
        if ($user->role === 'Seller') {
            return response()->json([
                'msg' => 'Anda tidak memiliki izin untuk membuat data ini'
            ], 403);
        }

        // Validasi data input
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'type' => 'required|in:Home,Work,Other',
            'primary' => 'required|boolean',
            'country' => 'required|in:' . implode(',', \App\Enums\Country::getValues()),
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'apartment_number' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Menambahkan user ID ke data yang divalidasi
        $validated['user_id'] = $user->id;

        // Membuat alamat
        $address = Address::create($validated);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            'data' => $address
        ], 200);
    }

    public function read(Request $request)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Memeriksa apakah role user adalah Admin
        if ($user->role == 'Admin') {
            // Admin dapat melihat semua alamat
            $addresses = Address::all();
            return response()->json([
                'msg' => 'Data Alamat Keseluruhan',
                'data' => $addresses
            ], 200);
        } else {
            // Pengguna non-admin hanya dapat melihat alamat mereka sendiri
            $addresses = Address::where('user_id', $user->id)->get();
            return response()->json([
                'msg' => 'Data Alamat Pengguna',
                'data' => $addresses
            ], 200);
        }
    }

    public function readById(Request $request, $id)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Memastikan bahwa pengguna adalah Admin
        if ($user->role !== 'Admin') {
            return response()->json([
                'msg' => 'Anda tidak memiliki izin untuk mengakses data ini'
            ], 403);
        }

        // Mendapatkan data berdasarkan ID
        $data = Address::find($id);

        // Memeriksa apakah data ditemukan
        if (!$data) {
            return response()->json([
                'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Validasi data input
        $validator = Validator::make($request->all(), [
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:Home,Work,Other',
            'primary' => 'sometimes|boolean',
            'country' => 'sometimes|in:' . implode(',', \App\Enums\Country::getValues()),
            'province' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'district' => 'sometimes|string|max:255',
            'street' => 'sometimes|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'apartment_number' => 'nullable|string|max:255',
            'postal_code' => 'sometimes|string|max:10',
            'phone_number' => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Mendapatkan alamat berdasarkan ID
        $address = Address::find($id);

        if ($address) {
            // Memeriksa apakah user adalah admin atau pemilik alamat
            if ($user->role == 'Admin' || $address->user_id == $user->id) {
                // Lakukan update data alamat
                $updated = $address->update($validated);

                // Periksa apakah update berhasil
                if ($updated) {
                    return response()->json([
                        'msg' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                        'data' => $address->fresh() // Refresh instance address setelah update
                    ], 200);
                } else {
                    return response()->json([
                        'msg' => 'Gagal mengupdate data'
                    ], 500);
                }
            } else {
                return response()->json([
                    'msg' => 'Anda tidak memiliki izin untuk mengupdate data ini'
                ], 403);
            }
        }

        return response()->json([
            'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        // Mendapatkan user dari request
        $user = $request->user();

        // Mendapatkan alamat berdasarkan ID
        $address = Address::find($id);

        if ($address) {
            // Memeriksa apakah user adalah admin atau pemilik alamat
            if ($user->role == 'Admin' || $address->user_id == $user->id) {
                $address->delete();

                return response()->json([
                    'msg' => 'Data alamat dengan ID: ' . $id . ' berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'msg' => 'Anda tidak memiliki izin untuk menghapus data alamat ini'
                ], 403);
            }
        }

        return response()->json([
            'msg' => 'Data alamat dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
