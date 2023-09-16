<?php

namespace App\Http\Controllers\Api\Individuals;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;
use App\Traits\Media;

class ProfileController extends Controller
{
    use ApiTrait, Media; 

    public function updateProfile(UpdateUserProfileRequest $request, $id)
 {
    $user = User::find($id);
    
    if (!$user) {
        return $this->errorMessage([], 'User not found', 404);
    }
    
    if (Auth::user()->id !== $user->id) {
        return $this->errorMessage([], 'You are not authorized to edit this account', 403);
    }

    $changes = false;

    if ($request->filled('name') && $user->name !== $request->input('name')) {
        $user->name = $request->input('name');
        $changes = true;
    }
    if ($request->filled('track') && $user->track !== $request->input('track')) {
        $user->track = $request->input('track');
        $changes = true;
    }
    if ($request->filled('bio') && $user->bio !== $request->input('bio')) {
        $user->bio = $request->input('bio');
        $changes = true;
    }

    if ($request->hasFile('imageUrl')) {
        $image = $request->file('imageUrl');
        $imagePath = $this->upload($image, 'users');
        $user->imageUrl = "images/users/$imagePath";
        $changes = true;
    }

    if ($changes) {
        $user->save();
    }
    $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'imageUrl' => $user->imageUrl,
        'track' => $user->track,
        'bio' => $user->bio,
    ];

    return $this->data(['user' => $userData], 'Profile updated successfully', 200);
 }

    public function updatePersonalInfo(UpdateUserProfileRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }

        if (Auth::user()->id !== $user->id) {
            return $this->errorMessage([], 'You are not authorized to edit this account', 403);
        }

        // Check if the user has a profile; create one if not
        $profile = $user->profile ?? new UserProfile();

        $changes = false;
        if ($request->input('governate') && $profile->governate !== $request->input('governate')) {
            $profile->governate = $request->input('governate');
            $changes = true;
        }
        if ($request->input('university') && $profile->university !== $request->input('university')) {
            $profile->university = $request->input('university');
            $changes = true;
        }
        if ($request->input('faculty') && $profile->faculty !== $request->input('faculty')) {
            $profile->faculty = $request->input('faculty');
            $changes = true;
        }
        if ($request->input('birthDate') && $profile->birthDate !== $request->input('birthDate')) {
            $profile->birthDate = $request->input('birthDate');
            $changes = true;
        }
        if ($request->input('emailProfile') && $profile->emailProfile !== $request->input('emailProfile')) {
            $profile->emailProfile = $request->input('emailProfile');
            $changes = true;
        }
        if ($request->input('phoneNumber') && $profile->phoneNumber !== $request->input('phoneNumber')) {
            $profile->phoneNumber = $request->input('phoneNumber');
            $changes = true;
        }
        if ($request->input('projects') && $profile->projects !== $request->input('projects')) {
            $profile->projects = $request->input('projects');
            $changes = true;
        }
        if ($request->input('progLanguages') && $profile->progLanguages !== $request->input('progLanguages')) {
            $profile->progLanguages = $request->input('progLanguages');
            $changes = true;
        }
        if ($request->input('cvUrl') && $profile->cvUrl !== $request->input('cvUrl')) {
            $profile->cvUrl = $request->input('cvUrl');
            $changes = true;
        }
        if ($request->input('githubUrl') && $profile->githubUrl !== $request->input('githubUrl')) {
            $profile->githubUrl = $request->input('githubUrl');
            $changes = true;
        }
        if ($request->input('linkedinUrl') && $profile->linkedinUrl !== $request->input('linkedinUrl')) {
            $profile->linkedinUrl = $request->input('linkedinUrl');
            $changes = true;
        }
        if ($request->input('behanceUrl') && $profile->behanceUrl !== $request->input('behanceUrl')) {
            $profile->behanceUrl = $request->input('behanceUrl');
            $changes = true;
        }
        if ($request->input('twitterUrl') && $profile->twitterUrl !== $request->input('twitterUrl')) {
            $profile->twitterUrl = $request->input('twitterUrl');
            $changes = true;
        }
        if ($request->input('facebookUrl') && $profile->facebookUrl !== $request->input('facebookUrl')) {
            $profile->facebookUrl = $request->input('facebookUrl');
            $changes = true;
        }
        
        if ($changes) {
            $user->profile()->save($profile);

            $userData = [
                'id' => $profile->id,
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

            return $this->data(['user' => $userData], 'Pesonal information updated successfully', 200);
        }

        return $this->errorMessage([], 'No changes to update', 422);
    }


    public function destroyAccount($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }
    
        // Check if the authenticated user is the owner of the account
        if (Auth::user()->id !== $user->id) {
            return $this->errorMessage([], 'You are not authorized to delete this account', 403);
        }
    
        $user->delete();
        return $this->successMessage('User account deleted successfully', 200);
    }
    
}
