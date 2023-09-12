<?php

namespace App\Http\Controllers\Api\Teams;

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
    
        $request = TeamRequest::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'type' => 'join',
            'status' => 'pending',
        ]);
    
        return $this->data(['team_id' => $team->id, 'request_id' => $request->id], 'Join request sent to the team.', 200);
}
    
public function acceptJoinRequest(TeamRequest $teamRequest, $id)
{
    $teamRequest = TeamRequest::findOrFail($id);

    if ($teamRequest->type !== 'join') {
        return $this->errorMessage([], 'Invalid request type.', 400);
    }

    // Check if the user is the leader of the team associated with the request
    if (!$this->isTeamLeader(Auth::user(), $teamRequest->team)) {
        return $this->errorMessage([], 'You are not authorized to accept this join request.', 403);
    }

    // Update the status of the request and handle accepted request
    $teamRequest->status = 'accepted';
    $teamRequest->save();

    // Increment the member count of the team
    $team = $teamRequest->team;
    $team->increment('members_count');

    // Attach the user to the team
    $user = $teamRequest->user;
    $user->teams()->attach($team->id);

    // Delete the request from the database
    $teamRequest->delete();

    return $this->data(['team_id' => $team->id, 'request_id' => $teamRequest->id],'Join request accepted successfully.', 200);
}

public function rejectJoinRequest($id)
{
    $teamRequest = TeamRequest::findOrFail($id);

    if ($teamRequest->type !== 'join') {
        return $this->errorMessage([], 'Invalid request type.', 400);
    }

    // Check if the user is the leader of the team associated with the request
    if (!$this->isTeamLeader(Auth::user(), $teamRequest->team)) {
        return $this->errorMessage([], 'You are not authorized to reject this join request.', 403);
    }

    // Update the status of the request and handle rejected request
    $teamRequest->status = 'rejected';
    $teamRequest->save();

    // Delete the request from the database
    $teamRequest->delete();

    return $this->data(['team_id' => $teamRequest->team->id, 'request_id' => $teamRequest->id],'Join request rejected successfully.', 200);
}

//--------------------------------------------------------------------


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

    if (!$existingRequest) {
        $existingRequest = TeamRequest::create([
            'user_id' => $invitee->id,
            'team_id' => $team->id,
            'type' => 'invite',
            'status' => 'pending',
        ]);
    }

    return $this->data(['team_id' => $team->id, 'request_id' => $existingRequest->id], 'User invited to the team.', 200);
}


public function acceptInviteRequest(TeamRequest $teamRequest, $id)
{
        $teamRequest = TeamRequest::findOrFail($id);

        if ($teamRequest->type !== 'invite') {
            return $this->errorMessage([], 'Invalid request type.', 400);
        }

        // Check if the user who was invited is the authenticated user
        if (!$this->isInvitedUser(Auth::user(), $teamRequest)) {
            return $this->errorMessage([], 'You are not authorized to accept this invite request.', 403);
        }

        // Update the status of the request and handle accepted request
        $teamRequest->status = 'accepted';
        $teamRequest->save();

        // Increment the member count of the team
        $team = $teamRequest->team;
        $team->increment('members_count');

       // Attach the user to the team
       $user = $teamRequest->user;
       $user->teams()->attach($team->id);
       $teamRequest->delete();

       return $this->data(['team_id' => $team->id, 'request_id' => $teamRequest->id],'Invite request accepted successfully.', 200);
}

public function rejectInviteRequest($id)
{
        $teamRequest = TeamRequest::findOrFail($id);
    
        if ($teamRequest->type !== 'invite') {
            return $this->errorMessage([], 'Invalid request type.', 400);
        }
    
        // Check if the user who was invited is the authenticated user
        if (!$this->isInvitedUser(Auth::user(), $teamRequest)) {
            return $this->errorMessage([], 'You are not authorized to reject this invite request.', 403);
        }
    
        // Fetch the team associated with the request
        $team = $teamRequest->team;
    
        // Update the status of the request and handle rejected request
        $teamRequest->status = 'rejected';
        $teamRequest->save();
        $teamRequest->delete();
    
        return $this->data(['team_id' => $team->id, 'request_id' => $teamRequest->id],'Invite request rejected successfully.', 200);
}
    

protected function isTeamLeader($user, $team)
 {
        return $user->id === $team->leader_id;
 }

protected function isInvitedUser($user, $teamRequest)
{
        return $user->id === $teamRequest->user_id;
}


//-------------------------------------------------------------------
public function removeJoinRequest($id)
{
    return $this->removeRequestOfType($id, 'join');
}

public function removeInviteRequest($id)
{
    return $this->removeRequestOfType($id, 'invite');
}

protected function removeRequestOfType($id, $type)
{
    $user = Auth::user();
    $teamRequest = TeamRequest::findOrFail($id);

    // Check if the request type matches
    if ($teamRequest->type !== $type) {
        return $this->errorMessage([], 'Invalid request type.', 400);
    }

    if ($type === 'join') {
        // Regular users can remove join requests
        if ($user->id !== $teamRequest->user_id) {
            return $this->errorMessage([], 'You are not authorized to remove this request.', 403);
        }
    } elseif ($type === 'invite') {
        // Team leader can remove invite requests
        if ($user->id !== $teamRequest->team->leader_id) {
            return $this->errorMessage([], 'You are not authorized to remove this request.', 403);
        }
    }

    // Remove the request
    $teamRequest->delete();

    return $this->successMessage('Request removed successfully.', 200);
}
}