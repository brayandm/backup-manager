<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function register()
    {
        $response = $this->post(
            '/api/v1/register',
            [
                'name' => 'Testing User',
                'email' => 'user@testing.com',
                'password' => '12345678',
            ]
        );

        $this->app->get('auth')->forgetGuards();

        return $response;
    }

    public function testRegister()
    {
        $this->markTestSkipped('Descomment Register in api');

        $response = $this->post(
            '/api/v1/register',
            [
                'name' => 'Testing User',
                'email' => 'user@testing.com',
                'password' => '12345678',
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([

            'access_token',
            'token_type',
            'policies',
            'expires_in',
        ]);

        $response->assertJsonPath('token_type', 'bearer');

        $this->assertDatabaseHas('users', ['name' => 'Testing User', 'email' => 'user@testing.com']);

        $this->app->get('auth')->forgetGuards();

        return $response;
    }

    public function testLoginAndLogout()
    {
        $this->markTestSkipped('Descomment Register in api');

        //Register format incorrect

        $response = $this->post(
            '/api/v1/register',
            [
                'name' => 'Testing User',
                'email' => 'user',
                'password' => '12345678',
            ]
        );

        $response->assertStatus(400);

        //Register

        $response = $this->register();

        //Login format incorrect

        $response = $this->post('/api/v1/login', [
            'email' => 'user',
            'password' => '12345678',
        ]);

        $response->assertStatus(400);

        $this->app->get('auth')->forgetGuards();

        //Login unauthenticated

        $response = $this->post('/api/v1/login', [
            'email' => 'user@testing.com',
            'password' => '123456789',
        ]);

        $response->assertStatus(401);

        $this->app->get('auth')->forgetGuards();

        //Login

        $response = $this->post('/api/v1/login', [
            'email' => 'user@testing.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);

        $this->app->get('auth')->forgetGuards();

        //Get token

        $token = $response['access_token'];

        //Logout

        $response = $this->post('/api/v1/logoutall', [], ['Authorization' => 'Bearer '.$token]);

        $response->assertStatus(200);

        $response->assertJsonPath('message', 'Successful logout');

        $this->app->get('auth')->forgetGuards();
    }
}
