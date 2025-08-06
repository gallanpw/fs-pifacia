<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Role;

class UserManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    // Melihat semua user
    public function index()
    {
        $user = JWTAuth::user();
        // if ($user->role !== 'Administrator') {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }
        
        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // $user = User::all();
        $user = User::with(['role'])->get()->makeHidden(['role']);
        return response()->json(['users' => $user]);
    }

    // Melihat user berdasarkan ID
    public function show($id)
    {
        $user = JWTAuth::user();
        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::withTrashed()->findOrFail($id)->makeHidden(['role']);
        if ($user->trashed()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    // Mengupdate user
    public function update(Request $request, $id)
    {
        $user = JWTAuth::user();
        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required|min:8|confirmed',
            'role_id'   => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->all());

        return response()->json(['user' => $user]);
    }

    // Soft delete user
    public function softDelete($id)
    {
        $user = JWTAuth::user();
        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete(); // Soft delete

        return response()->json(['message' => 'User deleted successfully']);
    }

}
