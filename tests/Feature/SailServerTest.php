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
        $response->assertSee("laravelsail/php84-composer:latest");
        $response->assertSee('bash -c "laravel new example-app --no-interaction && cd example-app && php ./artisan sail:install --with=mysql,redis,meilisearch,mailpit,selenium "', false);
    }

    public function test_different_php_versions_can_be_picked()
    {
        $response = $this->get('/example-app?php=80');

        $response->assertStatus(200);
        $response->assertSee("laravelsail/php80-composer:latest");
    }

    public function test_different_services_can_be_picked()
    {
        $response = $this->get('/example-app?with=pgsql,redis,selenium');

        $response->assertStatus(200);
        $response->assertSee('php ./artisan sail:install --with=pgsql,redis,selenium');
    }

    public function test_no_services_can_be_picked()
    {
        $response = $this->get('/example-app?with=none');

        $response->assertStatus(200);
        $response->assertSee('php ./artisan sail:install --with=none');
    }

    public function test_it_removes_duplicated_valid_services()
    {
        $response = $this->get('/example-app?with=redis,redis');

        $response->assertStatus(200);
        $response->assertSee('bash -c "laravel new example-app --no-interaction && cd example-app && php ./artisan sail:install --with=redis "', false);
    }

    public function test_it_adds_the_devcontainer_upon_request()
    {
        $response = $this->get('/example-app?with=pgsql&devcontainer');

        $response->assertStatus(200);
        $response->assertSee('bash -c "laravel new example-app --no-interaction && cd example-app && php ./artisan sail:install --with=pgsql --devcontainer"', false);
    }

    public function test_it_does_not_accepts_domains_with_a_dot()
    {
        $response = $this->get('/foo.test');

        $response->assertStatus(400);
        $response->assertSee('Invalid site name. Please only use alpha-numeric characters, dashes, and underscores.');
    }

    public function test_it_does_not_accept_empty_php_query_if_present()
    {
        $response = $this->get('/example-app?php');

        $response->assertStatus(400);
        $response->assertSee('Invalid PHP version. Please specify a supported version (74, 80, 81, 82, 83, or 84).');
    }

    public function test_it_does_not_accept_invalid_php_versions()
    {
        $response = $this->get('/example-app?php=1000');

        $response->assertStatus(400);
        $response->assertSee('Invalid PHP version. Please specify a supported version (74, 80, 81, 82, 83, or 84).');
    }

    public function test_it_does_not_accept_empty_with_query_when_present()
    {
        $response = $this->get('/example-app?with');

        $response->assertStatus(400);
        $response->assertSee('Invalid service name. Please provide one or more of the supported services (mysql, pgsql, mariadb, redis, rabbitmq, valkey, memcached, meilisearch, typesense, minio, mailpit, selenium, soketi) or "none".', false);
    }

    public function test_it_does_not_accept_invalid_services()
    {
        $response = $this->get('/example-app?with=redis,invalid_service_name');

        $response->assertStatus(400);
        $response->assertSee('Invalid service name. Please provide one or more of the supported services (mysql, pgsql, mariadb, redis, rabbitmq, valkey, memcached, meilisearch, typesense, minio, mailpit, selenium, soketi) or "none".', false);
    }

    public function test_it_does_not_accept_none_with_other_services()
    {
        $response = $this->get('/example-app?with=none,redis');

        $response->assertStatus(400);
        $response->assertSee('Invalid service name. Please provide one or more of the supported services (mysql, pgsql, mariadb, redis, rabbitmq, valkey, memcached, meilisearch, typesense, minio, mailpit, selenium, soketi) or "none".', false);
    }
}
