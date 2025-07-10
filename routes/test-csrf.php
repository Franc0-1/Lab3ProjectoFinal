<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Ruta para probar el CSRF token
Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_token' => session()->token(),
        'meta_token' => csrf_token()
    ]);
});

Route::post('/test-csrf-post', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF token válido',
        'data' => $request->all()
    ]);
});
