<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description','imageUrl', 'leader_id', 'members_count', 'is_full'];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_users', 'team_id', 'user_id');
    }

    public function teamRequests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    public function invitedUsers()
    {
        return $this->belongsToMany(User::class, 'team_requests', 'team_id', 'user_id')
            ->wherePivot('type', 'invite')
            ->wherePivot('status', 'pending');
    }
}
