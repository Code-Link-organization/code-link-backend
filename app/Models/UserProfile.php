<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
    'governate',
    'university',
    'faculty', 
    'birthDate',
    'emailProfile', 
    'phoneNumber',
    'projects',
    'progLanguages',
    'cvUrl',
    'githubUrl',
    'linkedinUrl',
    'behanceUrl',
    'facebookUrl',
     'twitterUrl'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
