<?php

namespace App\Http\Controllers\Api\Teams;

use App\Traits\ApiTrait;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeamRequest;
use App\Traits\Media;

class TeamController extends Controller
{
    use ApiTrait, Media; 

   public function index()
{
    $teams = Team::with(['members' => function ($query) {
        $query->select('users.id', 'name', 'email', 'imageUrl', 'track') // Explicitly specify the table for 'id'
            ->withPivot('team_id', 'user_id'); // Include the pivot data
    }])->orderBy('created_at', 'desc')->get();

    return response()->json([
        'result' => true,
        'message' => '',
        'data' => [
            'teams' => $teams,
        ],
    ]);
}

public function showTeam($id)
{
    try {
        $team = Team::with(['members' => function ($query) {
            $query->select('users.id', 'name', 'email', 'imageUrl', 'track') // Explicitly specify the table for 'id'
                ->withPivot('team_id', 'user_id'); // Include the pivot data
        }])->findOrFail($id);

        return response()->json([
            'result' => true,
            'message' => '',
            'data' => [
                'team' => $team,
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'result' => false,
            'message' => 'Team not found.',
            'data' => [],
        ], 404);
    }
}

    

public function storeTeam(TeamRequest $request)
{
    $user = Auth::user();
    try {
        // Upload and store the image if provided
        $imagePath = null;
        if ($request->hasFile('imageUrl')) {
            $image = $request->file('imageUrl');
            $imagePath = Media::upload($image, 'teams');
        }
        
        // Create the team with the provided data
        $team = Team::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'imageUrl' => $imagePath ? 'images/teams/' . $imagePath : null,
            'leader_id' => $user->id,
            'members_count' => 1,
        ]);

        $user->teams()->attach($team->id);

        // Construct the response JSON
        $response = [
            'result' => true,
            'message' => 'Team created successfully.',
            'data' => [
                'team' => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                    'imageUrl' => $team->imageUrl ?? null, // Use null if imageUrl is not set
                    'leader_id' => $team->leader_id,
                    'members_count' => $team->members_count,
                    'is_full' => $team->is_full,
                    'created_at' => $team->created_at,
                    'updated_at' => $team->updated_at,
                    'members' => [
                        [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'imageUrl' => $user->imageUrl,
                            'track' => $user->track,
                            'pivot' => [
                                'team_id' => $team->id,
                                'user_id' => $user->id,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($response, 201);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}



public function updateTeam(Request $request, $id)
{
    $authUser = Auth::user();
    $team = Team::find($id);

    if (!$team) {
        return $this->errorMessage([], 'Team not found.', 404);
    }

    if ($authUser->id !== $team->leader_id) {
        return $this->errorMessage([], 'You are not authorized to update this team.', 403);
    }

    $request->validate([
        'name' => 'nullable|string|between:2,255',
        'description' => 'nullable|string|min:3',
        'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
    ]);

    // Check and update name and description
    if ($request->filled('name')) {
        $team->name = $request->name;
    }

    if ($request->filled('description')) {
        $team->description = $request->description;
    }

    // Upload and update the team image if provided
    if ($request->hasFile('imageUrl')) {
        $image = $request->file('imageUrl');
        $imagePath = Media::upload($image, 'teams');
        $team->imageUrl = 'images/teams/' . $imagePath;
    }

    try {
        $team->save();

        // Fetch the user associated with the team
        $user = $team->leader;

        $response = [
            'result' => true,
            'message' => 'Team updated successfully.',
            'data' => [
                'team' => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                    'imageUrl' => $team->imageUrl,
                    'leader_id' => $team->leader_id,
                    'members_count' => $team->members_count,
                    'is_full' => $team->is_full,
                    'created_at' => $team->created_at,
                    'updated_at' => $team->updated_at,
                    'members' => [
                        [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'imageUrl' => $user->imageUrl,
                            'track' => $user->track,
                            'pivot' => [
                                'team_id' => $team->id,
                                'user_id' => $user->id,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($response, 201);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}



    public function destroyTeam($id)
    {
        $team = Team::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $team->leader_id) {
            return $this->errorMessage([], 'You are not authorized to delete this team.', 403);
        }

        try {
            $team->delete();
            return $this->successMessage('Team deleted successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorMessage([], $e->getMessage(), 500);
        }
    }


    public function leaveTeam($id)
    {
        $team = Team::findOrFail($id);
        $user = Auth::user();
    
        if ($team->leader_id === $user->id) {
            return $this->errorMessage([], 'You are the leader of this team. Use delete to leave.', 422);
        }
    
        // Detach the user from the team and decrement the members_count
        $user->teams()->detach($team->id);
        $team->decrement('members_count');
    
        $team->update(['is_full' => false]);
    
        if ($user->teams->contains($team->id)) {
            return $this->data(['team_id' => $team->id], 'Left the team successfully.', 200);
        } else {
            return $this->errorMessage([], 'You were not a member of this team.', 422);
        }
    }
    

    public function removeMember($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $user = Auth::user();

        if ($team->leader_id !== $user->id) {
            return $this->errorMessage([], 'You are not authorized to remove members from this team.', 403);
        }

        $member = User::find($userId);

        if (!$member) {
            return $this->errorMessage([], 'Member not found.', 404);
        }

        if (!$member->teams->contains($team->id)) {
            return $this->errorMessage([], 'This user is not a member of the team.', 404);
        }

        $member->teams()->detach($team->id);
        $team->decrement('members_count');

        return $this->data(['team_id' => $team->id],'Member removed from the team.', 200);
    }

}
