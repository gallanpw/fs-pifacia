<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use App\Models\Loan;
use App\Models\User;
use App\Models\Role;
use App\Models\Funder;
use App\Models\Farmer;

class LoanController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');  // Memastikan hanya user yang sudah login yang bisa mengakses
    // }

    // Menampilkan Semua Loan
    public function index()
    {
        // Ambil semua loan yang ada, termasuk yang non-aktif
        // $loan = Loan::all();
        $loan = Loan::with(['funder', 'farmer'])->get()->makeHidden(['funder', 'farmer']);

        return response()->json([
            'loan' => $loan,
        ]);
    }

    public function show($id)
    {
        // Mencari loan termasuk yang sudah di-soft delete
        $loan = Loan::withTrashed()->with(['funder', 'farmer'])->findOrFail($id)->makeHidden(['funder', 'farmer']);

        // Jika loan ditemukan namun sudah di-soft delete, kembalikan pesan loan not found
        if ($loan->trashed()) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        return response()->json([
            'loan' => $loan,
        ]);
    }

    // Create Loan
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
            'attachment_url' => 'required|mimes:pdf|max:500',
            'funder_id'   => 'required|exists:funders,id',
            'farmer_id'   => 'required|exists:farmers,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengecek apakah sudah ada loan aktif untuk farmer_id yang sama
        $existingLoan = Loan::where('farmer_id', $request->farmer_id)
        ->where('is_active', true)
        ->first();

        if ($existingLoan) {
            return response()->json(['message' => 'A loan already exists for this farmer and it is active. Please deactivate or delete the existing loan first.'], 400);
        }

        // Mengecek status aktif funder
        $funder = Funder::find($request->funder_id);
        if (!$funder || !$funder->is_active) {
            return response()->json(['message' => 'The Funder is inactive. Cannot create loan.'], 400);
        }

        // Mengecek status aktif farmer
        $farmer = Farmer::find($request->farmer_id);
        if (!$farmer || !$farmer->is_active) {
            return response()->json(['message' => 'The Farmer is inactive. Cannot create loan.'], 400);
        }

        // Mengonversi data JSON string menjadi array atau object
        $data = json_decode($request->input('data'), true); // decode menjadi array (atau bisa pakai false untuk object)

        // Menyimpan attachment file jika ada
        $attachmentUrl = null;

        // Jika ada file yang di-upload, simpan file tersebut dan ambil URL-nya
        if ($request->hasFile('attachment_url')) {
            // Menyimpan file di folder 'loans' pada local storage
            $filePath = $request->file('attachment_url')->store('loans', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        // Membuat Loan
        $loan = Loan::create([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl,
            'funder_id' => $request->funder_id,
            'farmer_id' => $request->farmer_id
        ]);

        return response()->json($loan, 201);
    }

    // Update Loan
    public function update(Request $request, $id)
    {
        $user = JWTAuth::user();
        $loan = Loan::withTrashed()->findOrFail($id);

        // Jika loan ditemukan namun sudah di-soft delete, kembalikan pesan loan not found
        if ($loan->trashed()) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'data' => 'json',
            'is_active' => 'boolean',
            'attachment_url' => 'nullable|max:500',
            'funder_id'   => 'required|exists:funders,id',
            'farmer_id'   => 'required|exists:farmers,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengecek apakah sudah ada loan aktif untuk farmer_id yang sama
        // $existingLoan = Loan::where('farmer_id', $request->farmer_id)
        // ->where('is_active', true)
        // ->first();

        // if ($existingLoan) {
        //     return response()->json(['message' => 'A loan already exists for this farmer and it is active. Please deactivate or delete the existing loan first.'], 400);
        // }

        // Mengecek status aktif funder
        $funder = Funder::find($request->funder_id);
        if (!$funder || !$funder->is_active) {
            return response()->json(['message' => 'The Funder is inactive. Cannot create loan.'], 400);
        }

        // Mengecek status aktif farmer
        $farmer = Farmer::find($request->farmer_id);
        if (!$farmer || !$farmer->is_active) {
            return response()->json(['message' => 'The Farmer is inactive. Cannot create loan.'], 400);
        }

        // Mengonversi data JSON string menjadi array atau object
        $data = json_decode($request->input('data'), true); // decode menjadi array (atau bisa pakai false untuk object)

        // Menyimpan attachment file jika ada
        $attachmentUrl = null;

        // Jika ada file yang di-upload, simpan file tersebut dan ambil URL-nya
        if ($request->hasFile('attachment_url')) {
            // Menyimpan file di folder 'loans' pada local storage
            $filePath = $request->file('attachment_url')->store('loans', 'public'); // Menggunakan disk 'public'
            $attachmentUrl = Storage::url($filePath);  // Mendapatkan URL file yang dapat diakses
        } else {
            // Jika tidak ada file di-upload, pastikan attachment_url adalah URL string yang valid
            $attachmentUrl = $request->input('attachment_url');
        }

        // Mengonversi is_active menjadi boolean
        $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        $loan->update([
            'data' => $data,
            'is_active' => $isActive,
            'attachment_url' => $attachmentUrl,
            'funder_id' => $request->funder_id,
            'farmer_id' => $request->farmer_id
        ]);

        // $loan->update($request->all());

        return response()->json($loan);
    }

    // Delete Loan
    public function softDelete($id)
    {
        $user = JWTAuth::user();
        $loan = Loan::withTrashed()->findOrFail($id);

        // Jika loan ditemukan namun sudah di-soft delete, kembalikan pesan loan not found
        if ($loan->trashed()) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        // Pastikan hanya pengguna dengan role 'Testing' atau 'Administrator' yang bisa menambah
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $loan->delete();

        return response()->json(['message' => 'Loan deleted successfully']);
    }
}