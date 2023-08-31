<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teamRequest()
    {
        return $this->belongsTo(TeamRequest::class, 'team_requests_id');
    }

    public function postComment()
    {
        return $this->belongsTo(PostComment::class, 'post_comments_id');
    }

    public function userFollower()
    {
        return $this->belongsTo(UserFollower::class, 'user_followers_id');
    }
}
