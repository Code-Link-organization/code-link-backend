<?php


namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;
use App\Traits\Media;

class UserController extends Controller
{
    use ApiTrait, Media; 
    
    public function index(){

    }


    public function getUserById($id)
    {

        // Find the user by their ID
        $user = User::find($id);

        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }

        $profile = $user->profile;

        // You can customize the data you want to return here
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'imageUrl' => $user->imageUrl,
            'track' => $user->track,
            'bio' => $user->bio,
            'governate' => $profile->governate,
            'university' => $profile->university,
            'faculty' => $profile->faculty,
            'birthDate' => $profile->birthDate,
            'emailProfile' => $profile->emailProfile,
            'phoneNumber' => $profile->phoneNumber,
            'projects' => $profile->projects,
            'progLanguages' => $profile->progLanguages,
            'cvUrl' => $profile->cvUrl,
            'githubUrl' => $profile->githubUrl,
            'linkedinUrl' => $profile->linkedinUrl,
            'behanceUrl' => $profile->behanceUrl,
            'facebookUrl' => $profile->facebookUrl,
            'twitterUrl' => $profile->twitterUrl,
        ];

        return $this->data(['user' => $userData], 'User retrieved successfully', 200);
    }

  
    
}
