<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Community extends Model
{
    use HasFactory, SoftDeletes;

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'community_users');
    }
}
