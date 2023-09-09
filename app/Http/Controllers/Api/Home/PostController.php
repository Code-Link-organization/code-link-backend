<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
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
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();
    
        // Transform the posts data to include user_name and user_imageUrl
        $postData = $posts->map(function ($post) {
            $data = $post->toArray();
            $data['user_name'] = $post->user->name;
            $data['user_imageUrl'] = $post->user->imageUrl; 
            unset($data['user']); 
            return $data;
        });
    
        return $this->data(compact('postData'));
    }
    

   public function showPost($id)
   {
       $post = Post::with('user')->find($id);
   
       if (!$post) {
           return $this->errorMessage([], 'Post not found', 404);
       }
   
       $postData = $post->toArray();
   
       // Replace the user_id with user's name and imageUrl
       $postData['user_name'] = $post->user->name; 
       $postData['user_imageUrl'] = $post->user->imageUrl; 
   
       unset($postData['user']);
   
       return $this->data(['post' => $postData], 'Post retrieved successfully', 200);
   }
   

   public function createPost(PostRequest $request)
   {        
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
   
       $post = Post::with('user')->find($post->id);
       
       $postData = $post->toArray();
       // Replace the user_id with user's name and imageUrl
       $postData['user_name'] = $post->user->name;
       $postData['user_imageUrl'] = $post->user->imageUrl;
       unset($postData['user']); 
   
       return $this->data(['post' => $postData], 'Post created successfully', 201);
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
       if (!$request->filled('content') && !$request->hasFile('file_path') && !$request->filled('remove_image')) {
           return $this->errorMessage([], 'No changes to update', 422);
       }

       // Update the post content if provided
       if ($request->filled('content')) {
           $post->content = $request->input('content');
       }

       // Handle image upload or removal, if provided
       if ($request->hasFile('file_path')) {
           $image = $request->file('file_path');
           $imagePath = $this->upload($image, 'posts');
           $post->image_path = "images/posts/$imagePath";
       } elseif ($request->filled('remove_image')) {
           // Check if the 'remove_image' input is provided and set to true
           // If 'remove_image' is true, delete the existing image and set image_path to null
           $this->delete($post->image_path);
           $post->image_path = null;
       }

       $post->save();

       $post = Post::with('user')->find($post->id);
       $postData = $post->toArray();

       // Replace the user_id with user's name and imageUrl
       $postData['user_name'] = $post->user->name; 
       $postData['user_imageUrl'] = $post->user->imageUrl; 
       unset($postData['user']); 

       if ($request->filled('content') || $request->hasFile('file_path') || $request->filled('remove_image')) {
           return $this->data(['post' => $postData], 'Post updated successfully', 200);
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

    
    public function getUserPosts($userId)
    {
        $user = User::find($userId);
    
        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }
    
        // Retrieve all posts for the user with the user relationship
        $posts = $user->posts()->orderBy('created_at', 'desc')->get();
    
        // Transform the posts data to include user_name and user_imageUrl
        $postData = $posts->map(function ($post) {
            $data = $post->toArray();
            $data['user_name'] = $post->user->name; 
            $data['user_imageUrl'] = $post->user->imageUrl; 
            unset($data['user']); 
            return $data;
        });
    
        return $this->data(compact('postData'));
    }


}
