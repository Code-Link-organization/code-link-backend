<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function createComment(Request $request)
    {
       // the implementation
    }

    public function showComments($id)
    {
       // the implementation
    }

    public function showComment($id)
    {
       // the implementation
    }

    public function editComment(Request $request, $id)
   {
        // the implementation
   }

   public function deleteComment($id)
   {
       // the implementation
   }
}
