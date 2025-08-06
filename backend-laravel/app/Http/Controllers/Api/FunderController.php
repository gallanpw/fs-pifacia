<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use App\Models\Funder;
use App\Models\User;
use App\Models\Role;

class FunderController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');  // Memastikan hanya user yang sudah login yang bisa mengakses
    // }

    // Menampilkan Semua Funder
    public function index()
    {
        // Ambil semua funder yang ada, termasuk yang non-aktif
        $funder = Funder::all();

        return response()->json([
            'funder' => $funder,
        ]);
    }

    public function show($id)
    {
        // Mencari funder termasuk yang sudah di-soft delete
        $funder = Funder::withTrashed()->findOrFail($id);

        // Jika funder ditemukan namun sudah di-soft delete, kembalikan pesan funder not found
        if ($funder->trashed()) {
            return response()->json(['message' => 'Funder not found'], 404);
        }

        return response()->json([
            'funder' => $funder,
        ]);
    }

    // Create Funder
    public function create(Request $request)
    {
        $user = JWTAuth::user();

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
            'is_active' => 'required',
            'attachment_url' => 'required|file|mimes:pdf|max:500',
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
            // Menyimpan file di folder 'funders' pada local storage
            $filePath = $request->file('attachment_url')->store('funders', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        // Membuat Funder
        $funder = Funder::create([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl
        ]);

        // $funder = Funder::create($request->all());

        return response()->json($funder, 201);
    }

    // Update Funder
    public function update(Request $request, $id)
    {
        // \Log::info($request->all());
        // error_log($request);

        $user = JWTAuth::user();

        // $funder = Funder::find($id);

        $funder = Funder::withTrashed()->findOrFail($id);

        // Jika funder ditemukan namun sudah di-soft delete, kembalikan pesan funder not found
        if ($funder->trashed()) {
            return response()->json(['message' => 'Funder not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa mengedit
        // if (!in_array($user->role, ['Testing', 'Administrator'])) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // error_log($request);

        $validator = Validator::make($request->all(), [
            'data' => 'json',  
            // 'data.name' => 'string',
            // 'data.phone_number' => 'string',
            // 'data.address' => 'string',
            'is_active' => 'boolean',
            'attachment_url' => 'nullable|max:500',  // Validasi untuk file
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengonversi data JSON string menjadi array atau object
        $data = json_decode($request->input('data'), true); // decode menjadi array (atau bisa pakai false untuk object)

        // \Log::info($validator->errors());
        // error_log($validator->errors());

        // Pastikan data tidak kosong setelah decoding
        // if (empty($data)) {
        //     return response()->json(['message' => 'Invalid data format'], 400);
        // }

        // Menyimpan attachment file jika ada
        $attachmentUrl = null;

        // Jika ada file yang di-upload, simpan file tersebut dan ambil URL-nya
        if ($request->hasFile('attachment_url')) {
            // Menyimpan file di folder 'funders' pada local storage
            $filePath = $request->file('attachment_url')->store('funders', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // if ($request->hasFile('attachment_url')) {
        //     $filePath = $request->file('attachment_url')->store('funders', 'public');
        //     $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file
        // }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        // Ambil data yang sudah di-parse dari form-data
        // $data = $request->input('data');  // Data akan berformat array yang sudah ter-validate

        // Membuat Funder
        // $funder = Funder::create([
        //     'data' => $data,
        //     'is_active' => $isActive,
        //     'attachment_url' => $attachmentUrl
        // ]);

        // $funder = Funder::find($id);
        $funder->update([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl
        ]);

        error_log($funder->update());

        // $funder->update($request->all());

        return response()->json($funder);
    }

    // Delete Funder
    public function softDelete($id)
    {
        $user = JWTAuth::user();
        $funder = Funder::withTrashed()->findOrFail($id);

        // Jika funder ditemukan namun sudah di-soft delete, kembalikan pesan funder not found
        if ($funder->trashed()) {
            return response()->json(['message' => 'Funder not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menghapus
        // if (!in_array($user->role, ['Testing', 'Administrator'])) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $funder->delete();

        return response()->json(['message' => 'Funder deleted successfully']);
    }
}