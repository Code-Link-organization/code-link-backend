<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserLogin()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);

        // Assert that the response contains the user's data and a token
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'token',
                ],
            ],
        ]);

        $responseData = $response->json();
        $this->assertNotNull($responseData['data']['user']['token']);
    }
}

