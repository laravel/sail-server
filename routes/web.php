<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

Route::redirect('/', 'https://laravel.com/docs', 302);

Route::get('/{name}', function (Request $request, $name) {
    try {
        Validator::validate(['name' => $name], ['name' => 'string|alpha_dash']);
    } catch (ValidationException $e) {
        return response('Invalid site name. Please only use alpha-numeric characters, dashes, and underscores.', 400);
    }

    $php = $request->query('php', '81');

    $services = $request->query('with', 'mysql,redis,meilisearch,mailhog,selenium');

    $devcontainer = $request->has('devcontainer') ? '--devcontainer' : '';

    $script = str_replace(
        ['{{ php }}', '{{ name }}', '{{ services }}', '{{ devcontainer }}'],
        [$php, $name, $services, $devcontainer],
        file_get_contents(resource_path('scripts/php.sh'))
    );

    return response($script, 200, ['Content-Type' => 'text/plain']);
});
