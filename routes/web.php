<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/7.4/{name}', function ($name) {
    return response(
        str_replace('{{ name }}', $name, file_get_contents(resource_path('scripts/php74.sh'))),
        200,
        ['Content-Type' => 'text/plain']
    );
});
