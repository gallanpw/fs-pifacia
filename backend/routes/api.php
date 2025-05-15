<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleManagementController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\Api\FunderController;
use App\Http\Controllers\Api\FarmerController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Middleware\CorsMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * route "/register"
 * @method "POST"
 */
Route::middleware('auth:api')->post('/register', [AuthController::class, 'register'])->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');

/**
 * route "/user"
 * @method "GET"
 */
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * route "/dashboard"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
// Route::middleware([CorsMiddleware::class])->get('/dashboard', [AuthController::class, 'dashboard']);



// Menampilkan Semua Role
Route::middleware('auth:api')->get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');

// Menampilkan Role berdasarkan ID
Route::middleware('auth:api')->get('/roles/{id}', [RoleManagementController::class, 'show'])->name('roles.show');

// Create Role
Route::middleware('auth:api')->post('/roles', [RoleManagementController::class, 'create'])->name('roles.create');

// Edit Role
Route::middleware('auth:api')->put('/roles/{id}', [RoleManagementController::class, 'edit'])->name('roles.edit');

// Soft Delete Role
Route::middleware('auth:api')->delete('/roles/{id}', [RoleManagementController::class, 'softDelete'])->name('roles.delete');



// Endpoint untuk melihat semua user (hanya bisa diakses oleh Administrator)
Route::middleware('auth:api')->get('/users', [UserManagementController::class, 'index'])->name('users.index');

// Endpoint untuk melihat user by ID (soft delete handled)
Route::middleware('auth:api')->get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');

// Endpoint untuk edit user
Route::middleware('auth:api')->put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');

// Endpoint untuk soft delete user
Route::middleware('auth:api')->delete('/users/{id}', [UserManagementController::class, 'softDelete'])->name('users.delete');



// Menampilkan Semua Funder
Route::middleware('auth:api')->get('/funders', [FunderController::class, 'index'])->name('funders.index');
// Menampilkan Funder berdasarkan ID
Route::middleware('auth:api')->get('/funders/{id}', [FunderController::class, 'show'])->name('funders.show');
// Create Funder
Route::middleware('auth:api')->post('/funders', [FunderController::class, 'create'])->name('funders.create');
// Edit Funder
Route::middleware('auth:api')->put('/funders/{id}', [FunderController::class, 'update'])->name('funders.update');
// Soft Delete Funder
Route::middleware('auth:api')->delete('/funders/{id}', [FunderController::class, 'softDelete'])->name('funders.delete');



// Menampilkan Semua Farmer
Route::middleware('auth:api')->get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
// Menampilkan Farmer berdasarkan ID
Route::middleware('auth:api')->get('/farmers/{id}', [FarmerController::class, 'show'])->name('farmers.show');
// Create Farmer
Route::middleware('auth:api')->post('/farmers', [FarmerController::class, 'create'])->name('farmers.create');
// Edit Farmer
Route::middleware('auth:api')->put('/farmers/{id}', [FarmerController::class, 'update'])->name('farmers.update');
// Soft Delete Farmer
Route::middleware('auth:api')->delete('/farmers/{id}', [FarmerController::class, 'softDelete'])->name('farmers.delete');



// Menampilkan Semua Loan
Route::middleware('auth:api')->get('/loans', [LoanController::class, 'index'])->name('loans.index');
// Menampilkan Loan berdasarkan ID
Route::middleware('auth:api')->get('/loans/{id}', [LoanController::class, 'show'])->name('loans.show');
// Create Loan
Route::middleware('auth:api')->post('/loans', [LoanController::class, 'create'])->name('loans.create');
// Edit Loan
Route::middleware('auth:api')->put('/loans/{id}', [LoanController::class, 'update'])->name('loans.update');
// Soft Delete Loan
Route::middleware('auth:api')->delete('/loans/{id}', [LoanController::class, 'softDelete'])->name('loans.delete');