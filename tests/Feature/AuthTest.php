<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(){
        // $this->withoutExceptionHandling();
        $data = [
            'name' => 'John Doe',
            'email' => 'j@d.com',
            'password' => 'password',
        ];
        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe'
        ]);
    }

    public function test_user_can_log_in()
    {
        $user = new \App\Models\User;
        $user->name = 'Flori';
        $user->email = 'f@f.com';
        $user->password = Hash::make('barcelona');
        $user->save();
        $response = $this->post('/api/auth/login', [
            'email' => 'f@f.com',
            'password' => 'barcelona'
        ]);
        $response->assertOk(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_user_must_enter_name_email_and_password_to_register(){
        $response = $this->post('/api/auth/register');
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ]);
    }
}
