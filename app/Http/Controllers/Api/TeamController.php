<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeamRequest;

class TeamController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $teams = Team::with('members')->get();
        return $this->data(compact('teams'));
    }

    public function showTeam($id)
{
    try {
        $team = Team::with('members')->findOrFail($id);
        return $this->data(compact('team'));
    } catch (\Exception $e) {
        return $this->errorMessage([], 'Team not found.', 404);
    }
}


public function storeTeam(Request $request)
{
    $user = Auth::user();
    $data = $request->all();

    try {
        $team = Team::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'leader_id' => $user->id, 
            'members_count' => 1,
        ]);

        $user->teams()->attach($team->id);

        return $this->successMessage('Team created successfully.', 201, [
            'team' => $team->fresh()->load('members') // Load the updated team data with members
        ]);
    } catch (\Exception $e) {
        return $this->errorMessage([], $e->getMessage());
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
    ]);

    if ($request->filled('name')) {
        $team->name = $request->name;
    }

    if ($request->filled('description')) {
        $team->description = $request->description;
    }

    try {
        $team->save();
        return $this->successMessage('Team updated successfully.', 200, [
            'team' => $team->fresh()->load('members') // Load the updated team data with members
        ]);
    } catch (\Exception $e) {
        return $this->errorMessage([], $e->getMessage());
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

        $user->teams()->detach($team->id);
        $team->decrement('members_count');
        $team->update(['is_full' => false]);

        return $this->successMessage('Left the team successfully.', 200);
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

        return $this->successMessage('Member removed from the team.', 200);
    }

}
