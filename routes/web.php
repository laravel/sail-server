<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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
    Validator::validate(['name' => $name], ['name' => 'string|alpha_dash']);

    $php = $request->query('php', '80');

    $services = $request->query('with', 'mysql,redis,meilisearch,mailhog,selenium');

    $script = str_replace(
        ['{{ php }}', '{{ name }}', '{{ services }}'],
        [$php, $name, $services],
        file_get_contents(resource_path('scripts/php.sh'))
    );

    return response($script, 200, ['Content-Type' => 'text/plain']);
});
