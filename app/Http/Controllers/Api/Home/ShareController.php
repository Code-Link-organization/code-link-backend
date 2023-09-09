<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;

class ShareController extends Controller
{
    use ApiTrait;

    public function sharePost(Request $request, Post $post)
    {
        try {
            $user = Auth::user(); 
    
            // Create a new share record for the authenticated user and the post
            $share = new Share();
            $share->user_id = $user->id;
            $share->post_id = $post->id;
            $share->owneruser_id = $post->user_id; // Set the owneruser_id to the original post owner's ID
            $share->save();
    
            // Increment the shares_count in the posts table
            $post->increment('shares_count');
    
            $postData = [
                'id' => $share->id,
                'post_id' => $post->id,
                "content" => $post->content,
                "image_path" => $post->image_path,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_image' => $user->imageUrl,
                'owneruser_id' => $post->user_id,
                'owner_name' => $post->user->name,
                'owner_image' => $post->user->imageUrl, 
            ];
    
            return $this->data($postData, 'Post shared successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage([], 'An error occurred while sharing the post', 500);
        }
    }
    

    public function removeShare(Share $share)
    {
        $user = Auth::user(); 

        // Check if the authenticated user owns this share
        if ($share->user_id === $user->id) {
            $post = $share->post;

            // Delete the share
            $share->delete();

            // Decrement the shares_count in the posts table
            $post->decrement('shares_count');

            return $this->data(['share_id' => $share->id,'post_id' => $post->id], 'Share removed successfully', 200);
        }

        return $this->errorMessage([], 'Share not found', 404);
    }
}
