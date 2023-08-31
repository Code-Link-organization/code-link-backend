<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    public function mentors()
    {
        return $this->hasMany(Mentor::class);
    }

}
