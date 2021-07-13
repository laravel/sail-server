<?php

namespace Tests\Feature;

use Tests\TestCase;

class SailServerTest extends TestCase
{
    public function test_it_can_return_the_sail_install_script()
    {
        $response = $this->get('/example-app');

        $response->assertStatus(200);
        $response->assertSee("laravelsail/php80-composer:latest");
        $response->assertSee('bash -c "laravel new example-app && cd example-app && php ./artisan sail:install --with=mysql,redis,meilisearch,mailhog,selenium"', false);
    }

    public function test_different_services_can_be_picked()
    {
        $response = $this->get('/example-app?with=postgresql,redis,selenium');

        $response->assertStatus(200);
        $response->assertSee('php ./artisan sail:install --with=postgresql,redis,selenium');
    }
}
