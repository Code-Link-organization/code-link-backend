<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUserNotifications()
    {
        $user = auth()->user();
    
        // Fetch invite requests sent to the user
        $inviteRequests = TeamRequest::where('type', 'invite')
            ->where('user_id', $user->id)
            ->with(['team'])
            ->get();
    
        // Fetch join requests for teams the user is the leader of
        $leaderTeams = Team::where('leader_id', $user->id)->pluck('id');
    
        $joinRequests = TeamRequest::whereIn('team_id', $leaderTeams)
            ->where('type', 'join')
            ->with(['user'])
            ->get();
    
        // Combine invite and join requests into a single collection
        $notifications = $inviteRequests->concat($joinRequests);
    
        // Sort the combined collection by created_at in descending order
        $sortedNotifications = $notifications->sortByDesc('created_at')->values()->all();
    
        return response()->json([
            'result' => true,
            'message' => 'User notifications retrieved successfully.',
            'data' => [
                'notifications' => $sortedNotifications,
            ],
        ]);
    }
    
}
