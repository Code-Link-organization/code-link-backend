<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testDeletePost()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);


        $this->actingAs($user);


        $response = $this->delete('/posts/' . $post->id);


        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Post deleted successfully'
        ]);


        $response->assertDeleted($post);
    }

    public function testCreatePost()
    {

        $user = User::factory()->create();
        $postData = [
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'user_id' => $user->id,
        ];


        $this->actingAs($user);


        $response = $this->post('/posts', $postData);


        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Post created successfully',
            'data' => [
                'title' => 'Test Post',
                'content' => 'This is a test post',
                'user_id' => $user->id,
            ],
        ]);


        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'user_id' => $user->id,
        ]);
    }

    public function testGetPosts()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);


        $response = $this->get('/api/home/posts');


        $response->assertStatus(200);


        $response->assertJsonFragment([
            'user_name' => $user->name,
            'user_imageUrl' => $user->imageUrl,

        ]);
    }


    public function testEditPost()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);


        $requestData = [
            'content' => 'New content',

        ];


        $response = $this->put("/api/home/posts/{$post->id}", $requestData);


        $response->assertStatus(200);


        $response->assertJsonFragment([
            'user_name' => $user->name,
            'user_imageUrl' => $user->imageUrl,
            'content' => 'New content',
            
        ]);
    }
}
