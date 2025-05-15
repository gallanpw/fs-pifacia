<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use App\Models\Farmer;
use App\Models\User;
use App\Models\Role;

class FarmerController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');  // Memastikan hanya user yang sudah login yang bisa mengakses
    // }

    // Menampilkan Semua Farmer
    public function index()
    {
        // Ambil semua farmer yang ada, termasuk yang non-aktif
        $farmer = Farmer::all();

        return response()->json([
            'farmer' => $farmer,
        ]);
    }

    public function show($id)
    {
        // Mencari farmer termasuk yang sudah di-soft delete
        $farmer = Farmer::withTrashed()->findOrFail($id);

        // Jika farmer ditemukan namun sudah di-soft delete, kembalikan pesan farmer not found
        if ($farmer->trashed()) {
            return response()->json(['message' => 'Farmer not found'], 404);
        }

        return response()->json([
            'farmer' => $farmer,
        ]);
    }

    // Create Farmer
    public function create(Request $request)
    {
        $user = JWTAuth::user();

        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
            'is_active' => 'required',
            'attachment_url' => 'required|mimes:pdf|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengonversi data JSON string menjadi array atau object
        $data = json_decode($request->input('data'), true); // decode menjadi array (atau bisa pakai false untuk object)

        // Menyimpan attachment file jika ada
        $attachmentUrl = null;

        // Jika ada file yang di-upload, simpan file tersebut dan ambil URL-nya
        if ($request->hasFile('attachment_url')) {
            // Menyimpan file di folder 'farmers' pada local storage
            $filePath = $request->file('attachment_url')->store('farmers', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        // Membuat Farmer
        $farmer = Farmer::create([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl
        ]);

        return response()->json($farmer, 201);
    }

    // Update Farmer
    public function update(Request $request, $id)
    {
        $user = JWTAuth::user();
        $farmer = Farmer::withTrashed()->findOrFail($id);

        // Jika farmer ditemukan namun sudah di-soft delete, kembalikan pesan farmer not found
        if ($farmer->trashed()) {
            return response()->json(['message' => 'Farmer not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'data' => 'json',
            'is_active' => 'boolean',
            'attachment_url' => 'nullable|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengonversi data JSON string menjadi array atau object
        $data = json_decode($request->input('data'), true); // decode menjadi array (atau bisa pakai false untuk object)

        // Menyimpan attachment file jika ada
        $attachmentUrl = null;

        // Jika ada file yang di-upload, simpan file tersebut dan ambil URL-nya
        if ($request->hasFile('attachment_url')) {
            // Menyimpan file di folder 'farmers' pada local storage
            $filePath = $request->file('attachment_url')->store('farmers', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        $farmer->update([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl
        ]);

        return response()->json($farmer);
    }

    // Delete Farmer
    public function softDelete($id)
    {
        $user = JWTAuth::user();
        $farmer = Farmer::withTrashed()->findOrFail($id);

        // Jika farmer ditemukan namun sudah di-soft delete, kembalikan pesan farmer not found
        if ($farmer->trashed()) {
            return response()->json(['message' => 'Farmer not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $farmer->delete();

        return response()->json(['message' => 'Farmer deleted successfully']);
    }
}