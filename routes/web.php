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
    $services = $request->query('with', 'mysql,redis,meilisearch,mailhog,selenium');
    $git = $request->query('git', false);
    $github = $request->query('github', false);

    $script = str_replace([
        '{{ name }}',
        '{{ services }}',
        '{{ git }}',
        '{{ github }}',
        '{{ githubFlags }}',
    ], [
        $name,
        $services,
        $git !== false || $github !== false ? 'true' : 'false',
        $github !== false ? 'true' : 'false',
        $github !== false && $github !== 'true' && $github !== null ? $github : '--private',
    ], file_get_contents(resource_path('scripts/php80.sh')));

    return response($script, 200, ['Content-Type' => 'text/plain']);
});
