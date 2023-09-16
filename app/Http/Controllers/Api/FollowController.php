<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
        ]);

        $user = auth()->user();

        $userToFollow = User::find($request->user_id);

        $user->follows()->attach($userToFollow);

        return response()->json([
            'message' => 'You are now following this user.',
        ]);
    }


    public function unfollow(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
        ]);

        $user = auth()->user();

        $userToUnfollow = User::find($request->user_id);

        
        $user->follows()->detach($userToUnfollow);

        return response()->json([
            'message' => 'You have unfollowed this user.',
        ]);
    }
}


