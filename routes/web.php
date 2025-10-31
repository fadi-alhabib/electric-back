<?php

use Illuminate\Support\Facades\Route;


Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});


// Route::get('{any}', function () {
//     return view('react.index');
// })->where('any', '.*');
