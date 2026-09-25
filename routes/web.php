<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->file(base_path('front/index.html'));
});

Route::redirect('/login', '/admin');

Route::get('/setup/ser1', function () {
    set_time_limit(0);

    Artisan::call('migrate:fresh', [
        '--seed' => true,
        '--force' => true,
    ]);

    return response(Artisan::output(), 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
});

$frontFile = function (string $folder, string $path) {
    $root = realpath(base_path('front/'.$folder));
    $file = realpath(base_path('front/'.$folder.'/'.$path));

    if ($root === false || $file === false || ! is_file($file) || ! str_starts_with($file, $root.DIRECTORY_SEPARATOR)) {
        abort(404);
    }

    $mime = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'mjs' => 'application/javascript; charset=UTF-8',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'html' => 'text/html; charset=UTF-8',
        'json' => 'application/json',
    ];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return response()->file($file, [
        'Content-Type' => $mime[$extension] ?? 'application/octet-stream',
    ]);
};

Route::get('/Photos/{path}', fn (string $path) => $frontFile('Photos', $path))->where('path', '.*');
Route::get('/assets/{path}', fn (string $path) => $frontFile('assets', $path))->where('path', '.*');
