<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    
    public function testUserRegistration()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123', 
        ];
    
        $response = $this->postJson('/api/user/signup', $userData);
    
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }
    
}
