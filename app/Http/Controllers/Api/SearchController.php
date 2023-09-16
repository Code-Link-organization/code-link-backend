<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'result' => true,
            'message' => 'User search results retrieved successfully.',
            'data' => [
                'users' => $users,
            ],
        ]);
    }
}
