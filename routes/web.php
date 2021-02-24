<?php

use Illuminate\Http\Request;
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

Route::get('/{name}', function (Request $request, $name) {
    $services = $request->query('services', 'mysql,redis,meilisearch,mailhog,selenium');

    $script = file_get_contents(resource_path('scripts/php80.sh'));

    $script = str_replace('{{ name }}', $name, $script);
    $script = str_replace('{{ services }}', $services, $script);

    return response($script, 200, ['Content-Type' => 'text/plain']);
});
