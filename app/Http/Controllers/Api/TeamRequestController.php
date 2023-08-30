<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;

class TeamRequestController extends Controller
{
    use ApiTrait;

    public function inviteTeam(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $user = Auth::user();

        if ($team->leader_id !== $user->id) {
            return $this->errorMessage([], 'You are not authorized to invite to this team.', 403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $invitee = User::find($data['user_id']);
        if (!$invitee) {
            return $this->errorMessage([], 'User not found.', 404);
        }

        // Check if the invitee is already a member of the team
        if ($team->members->contains($invitee)) {
            return $this->errorMessage([], 'User is already a member of the team.', 400);
        }

        // Check if there is an existing invite
        $existingRequest = TeamRequest::where('user_id', $invitee->id)
            ->where('team_id', $team->id)
            ->where('type', 'invite')
            ->first();

        if ($existingRequest) {
            return $this->errorMessage([], 'An invite request is already pending for this user.', 400);
        }

        TeamRequest::create([
            'user_id' => $invitee->id,
            'team_id' => $team->id,
            'type' => 'invite',
            'status' => 'pending',
        ]);

        return $this->successMessage('User invited to the team.', 200);
    }

    public function joinTeam($id)
    {
        $team = Team::findOrFail($id);
        $user = Auth::user();

        if ($team->is_full) {
            return $this->errorMessage([], 'This team is already full.', 422);
        }

        // Check if the user is already a member of the team
        if ($team->members->contains($user)) {
            return $this->errorMessage([], 'You are already a member of the team.', 400);
        }

        // Check if there is an existing join request
        $existingRequest = TeamRequest::where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->where('type', 'join')
            ->first();

        if ($existingRequest) {
            return $this->errorMessage([], 'A join request is already pending for this user.', 400);
        }

        TeamRequest::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'type' => 'join',
            'status' => 'pending',
        ]);

        return $this->successMessage('Join request sent to the team.', 200);
    }

}
