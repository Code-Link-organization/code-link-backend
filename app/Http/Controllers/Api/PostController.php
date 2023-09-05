<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PostRequest;
use App\Traits\ApiTrait;
use App\Traits\Media;

class PostController extends Controller
{
    use ApiTrait, Media; 

   public function getPosts()
   {
      $posts = Post::orderBy('created_at', 'desc')->get();
      return $this->data(compact('posts'));
   }

   public function showPost($id)
   {
       $post = Post::find($id);

       if (!$post) {
           return $this->errorMessage([], 'Post not found', 404);
       }

       $postData = $post->toArray();

       return $this->data($postData, 'Post retrieved successfully', 200);
   }


    public function createPost(PostRequest $request)
    {        
        // Check if either 'content' or 'file_path' is provided
        if (!$request->filled('content') && !$request->hasFile('file_path')) {
            return $this->errorMessage([], 'Either content or an image must be provided', 422);
        }
    
        // Create a new post
        $post = new Post;
        $post->user_id = Auth::id(); 
    
        // Set content if provided
        if ($request->filled('content')) {
            $post->content = $request->input('content');
        }
           
    // Handle image upload, if provided
    if ($request->hasFile('file_path')) {
        $image = $request->file('file_path');
        $imagePath = $this->upload($image, 'posts');
        $post->image_path = "images/posts/$imagePath"; 
    }

    
        $post->save();
        return $this->successMessage('Post created successfully', 201);
    }
    
    public function editPost(PostRequest $request, $id)
    {    
        $post = Post::find($id);
        if (!$post) {
            return $this->errorMessage([], 'Post not found', 404);
        }
    
        if ($post->user_id !== Auth::id()) {
            return $this->errorMessage([], 'You are not authorized to edit this post', 403);
        }
    
        // Check if there are any changes to update
        if (!$request->filled('content') && !$request->hasFile('file_path')) {
            return $this->errorMessage([], 'No changes to update', 422);
        }
    
        // Update the post content if provided
        if ($request->filled('content')) {
            $post->content = $request->input('content');
        }
    
        // Handle image upload, if provided
    if ($request->hasFile('file_path')) {
        $image = $request->file('file_path');
        $imagePath = $this->upload($image, 'posts');
        $post->image_path = "images/posts/$imagePath"; 
    }

        $post->save();
    
        if ($request->filled('content') || $request->hasFile('file_path')) {
            return $this->successMessage('Post updated successfully', 200);
        } else {
            return $this->errorMessage([], 'No changes to update', 422);
        }
    }

  
    public function deletePost($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->errorMessage([], 'Post not found', 404);
        }

        if ($post->user_id !== Auth::id()) {
            return $this->errorMessage([], 'You are not authorized to edit this post', 403);
        }

        // Delete the post and associated image, if it exists
        if ($post->image_path) {
            $this->delete($post->image_path);
        }
        
        $post->delete();
        return $this->successMessage('Post deleted successfully', 200);
    }
}
