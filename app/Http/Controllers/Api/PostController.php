<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function createPost(Request $request)
    {
       // the implementation
    }

    public function getPosts()
    {
       // the implementation
    }

    public function showPost($id)
    {
       // the implementation
    }

    public function editPost(Request $request, $id)
   {
        // the implementation
   }

   public function deletePost($id)
   {
       // the implementation
   }
}
