<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Role;
use App\Models\User;

class RoleManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    // Menampilkan Semua Role
    public function index()
    {
        // Ambil semua role yang ada, termasuk yang non-aktif
        $role = Role::all();

        return response()->json([
            'roles' => $role,
        ]);
    }

    public function show($id)
    {
        // Mencari role termasuk yang sudah di-soft delete
        $role = Role::withTrashed()->findOrFail($id);

        // Jika role ditemukan namun sudah di-soft delete, kembalikan pesan role not found
        if ($role->trashed()) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json([
            'role' => $role,
        ]);
    }

    // Create Role (Guest, Testing, Admin can create)
    public function create(Request $request)
    {
        $user = JWTAuth::user();

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|unique:roles,name',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if ($user->role !== 'Guest' && $user->role !== 'Testing' && $user->role !== 'Administrator') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Validasi role yang diizinkan
        // if (!in_array($user->role, ['Guest', 'Testing', 'Administrator'])) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $role = Role::create([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['role' => $role], 201);
    }

    // Edit Role
    public function edit(Request $request, $id)
    {
        $user = JWTAuth::user();
        $role = Role::withTrashed()->findOrFail($id);

        // Jika role ditemukan namun sudah di-soft delete, kembalikan pesan role not found
        if ($role->trashed()) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        
        // Checking the permission for editing role
        if ($user->role === 'Guest' && $role->id !== $user->id) {
            return response()->json(['message' => 'Forbidden to edit this role'], 403);
        }

        if ($user->role === 'Testing' && $role->name === 'Administrator') {
            return response()->json(['message' => 'Cannot edit Administrator role'], 403);
        }

        // if ($role->name === 'Administrator') {
        //     return response()->json(['message' => 'Cannot edit Administrator role'], 403);
        // }

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['role' => $role]);
    }

    // Soft delete role (Only admin can delete)
    public function softDelete($id)
    {
        $user = JWTAuth::user();

        $role = Role::withTrashed()->findOrFail($id);
        if ($role->trashed()) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // if ($user->role !== 'Administrator') {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }
        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id && $user->role_id !== Role::where('name', 'Testing')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
