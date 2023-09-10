<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
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

        // You can customize the data you want to return here
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'imageUrl' => $user->imageUrl,
            'track' => $user->track
        ];

        return $this->data(['user' => $userData], 'User retrieved successfully', 200);
    }

    public function updateProfile(UpdateUserProfileRequest $request, $id)
    {
        $user = User::find($id);
    
        $changes = false;
        
        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }

        if ($request->input('name') && $user->name !== $request->input('name')) {
            $user->name = $request->input('name');
            $changes = true;
        }
        if ($request->input('track') && $user->track !== $request->input('track')) {
            $user->track = $request->input('track');
            $changes = true;
        }
        if ($request->input('bio') && $user->bio !== $request->input('bio')) {
            $user->bio = $request->input('bio');
            $changes = true;
        }
        if ($request->input('email_profile') && $user->email_profile !== $request->input('email_profile')) {
            $user->email_profile = $request->input('email_profile');
            $changes = true;
        }
        if ($request->input('cvUrl') && $user->cvUrl !== $request->input('cvUrl')) {
            $user->cvUrl = $request->input('cvUrl');
            $changes = true;
        }
        if ($request->input('githubUrl') && $user->githubUrl !== $request->input('githubUrl')) {
            $user->githubUrl = $request->input('githubUrl');
            $changes = true;
        }
        if ($request->input('linkedinUrl') && $user->linkedinUrl !== $request->input('linkedinUrl')) {
            $user->linkedinUrl = $request->input('linkedinUrl');
            $changes = true;
        }
        if ($request->input('behanceUrl') && $user->behanceUrl !== $request->input('behanceUrl')) {
            $user->behanceUrl = $request->input('behanceUrl');
            $changes = true;
        }
        if ($request->input('twitterUrl') && $user->twitterUrl !== $request->input('twitterUrl')) {
            $user->twitterUrl = $request->input('twitterUrl');
            $changes = true;
        }
        if ($request->input('facebookUrl') && $user->facebookUrl !== $request->input('facebookUrl')) {
            $user->facebookUrl = $request->input('facebookUrl');
            $changes = true;
        }
      
        if ($request->hasFile('imageUrl')) {
            $image = $request->file('imageUrl');
            $imagePath = $this->upload($image, 'users');
            $user->imageUrl = "images/users/$imagePath";
            $userData['imageUrl'] = $user->imageUrl; 
            $changes = true;
        }
    
        if ($changes) {
            $user->save();
            
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'imageUrl' => $user->imageUrl,
                'track' => $user->track,
                'bio' => $user->bio,
                'cvUrl' => $user->cvUrl,
                'email_profile' => $user->email_profile,
                'githubUrl' => $user->githubUrl,
                'linkedinUrl' => $user->linkedinUrl,
                'behanceUrl' => $user->behanceUrl,
                'facebookUrl' => $user->facebookUrl,
                'twitterUrl' => $user->twitterUrl,
            ];
        
            return $this->data(['user' => $userData], 'Profile updated successfully', 200);
        }
        
        return $this->errorMessage([], 'No changes to update', 422);
    }


    public function destroyAccount($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }
    
        $user->delete();
    
        return $this->successMessage('User account deleted successfully', 200);
    }
    
}
