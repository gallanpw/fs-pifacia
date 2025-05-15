<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return response()->json([
        'message' => [
            'Welcome to the API!',
            'This API provides various functionalities to manage data efficiently.',
            'Feel free to explore our documentation for more detailed usage.',
            'If you have any issues, please contact our support team.'
        ]
    ]);
});
