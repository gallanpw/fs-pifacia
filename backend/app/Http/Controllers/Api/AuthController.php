<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    // Register
    public function register(Request $request)
    {
        $user = JWTAuth::user();

        // Pastikan hanya Administrator yang bisa melakukan registrasi user
        if ($user->role_id !== Role::where('name', 'Administrator')->first()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'role_id'   => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Validasi apakah role yang dipilih aktif
        $role = Role::find($request->role_id);
        if (!$role || !$role->is_active) {
            return response()->json(['message' => 'Role is either inactive or does not exist'], 400);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role_id'   => $request->role_id
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,  
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),    
            'token'   => $token   
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!',  
            ]);
        }
    }

    // Get User (Dashboard)
    public function dashboard(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'message' => 'Welcome to Dashboard'
        ]);
    }
}
