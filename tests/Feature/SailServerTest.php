<?php

namespace Tests\Feature;

use Tests\TestCase;

class SailServerTest extends TestCase
{
    public function test_the_homepage_redirects_to_the_laravel_docs()
    {
        $this->get('/')->assertRedirect('https://laravel.com/docs');
    }

    public function test_it_can_return_the_sail_install_script()
    {
        $response = $this->get('/example-app');

        $response->assertStatus(200);
        $response->assertSee("laravelsail/php81-composer:latest");
        $response->assertSee('bash -c "laravel new example-app  && cd example-app && php ./artisan sail:install --with=mysql,redis,meilisearch,mailhog,selenium "', false);
    }

    public function test_different_services_can_be_picked()
    {
        $response = $this->get('/example-app?with=postgresql,redis,selenium');

        $response->assertStatus(200);
        $response->assertSee('php ./artisan sail:install --with=postgresql,redis,selenium');
    }

    public function test_it_adds_git_upon_request()
    {
        $response = $this->get('/example-app?with=postgres&git');

        $response->assertStatus(200);
        $response->assertSee('bash -c "laravel new example-app --git && cd example-app && php ./artisan sail:install --with=postgres "', false);
    }

    public function test_it_adds_the_devcontainer_upon_request()
    {
        $response = $this->get('/example-app?with=postgres&devcontainer');

        $response->assertStatus(200);
        $response->assertSee('bash -c "laravel new example-app  && cd example-app && php ./artisan sail:install --with=postgres --devcontainer"', false);
    }

    public function test_it_does_not_accepts_domains_with_a_dot()
    {
        $response = $this->get('/foo.test');

        $response->assertStatus(400);
        $response->assertSee('Invalid site name. Please only use alpha-numeric characters, dashes, and underscores.');
    }
}
