<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%')
        ->orWhere('track', 'like', '%' . $query . '%')
        ->get();

        return response()->json([
            'result' => true,
            'message' => 'User search results retrieved successfully.',
            'data' => [
                'users' => $users,
            ],
        ]);
    }

    public function searchTeams(Request $request)
{
    $query = $request->input('query'); 

    $teams = Team::where('name', 'like', '%' . $query . '%')
        ->orWhere('description', 'like', '%' . $query . '%')
        ->get();

    return response()->json([
        'result' => true,
        'message' => 'Team search results retrieved successfully.',
        'data' => [
            'teams' => $teams,
        ],
    ]);
}

}
