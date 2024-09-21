<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/user/change-password', [UserController::class, 'updatePassword']);
Route::middleware('auth:sanctum')->put('/user/update', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->post('/email/send-token', [AuthController::class, 'sendVerificationToken']);
Route::middleware('auth:sanctum')->post('/email/verify', [AuthController::class, 'verifyEmail']);
Route::middleware('auth:sanctum')->get('/movies', [MovieController::class, 'index']);
Route::middleware('auth:sanctum')->post('/movies/store', [MovieController::class, 'store']);
Route::middleware('auth:sanctum')->get('/movies', [MovieController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified'])->get('/restricted-movies', [MovieController::class, 'restrictedIndex']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', 'verified'])->get('/rota-protegida', function () {
    return response()->json(['message' => 'Acesso permitido!']);
});
