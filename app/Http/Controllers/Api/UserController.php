<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Media;
use App\Traits\ApiTrait;




class UserController extends Controller
{
    use ApiTrait, Media;
    public function index()
    {
    }
    public function createUser(Request $request)
    {
    }
    public function showUser($id)
    {
    }
    public function editUser(Request $request, $id)
    {

        // Retrieve the user with the given ID from the database
        $user = User::find($id);


        // check if the user profile exists in the database
        if (!$user) {
            return $this->errorMessage([], 'Uesr not found', 404);
        }

        // Check if there are any changes to update
        $changes = false;
        if ($request->input('name') && $user->name !== $request->input('name')) {
            $user->name = $request->input('name');
            $changes = true;
        }
        if ($request->input('bio') && $user->bio !== $request->input('bio')) {
            $user->bio = $request->input('bio');
            $changes = true;
        }
        if ($request->input('email_profile') && $user->email !== $request->input('email')) {
            $user->email = $request->input('email');
            $changes = true;
        }
        if ($request->input('gender') && $user->gender !== $request->input('gender')) {
            $user->gender = $request->input('gender');
            $changes = true;
        }
        if ($request->input('phoneNumber') && $user->phoneNumber !== $request->input('phoneNumber')) {
            $user->phoneNumber = $request->input('phoneNumber');
            $changes = true;
        }
        if ($request->input('years_of_experience') && $user->years_of_experience !== $request->input('years_of_experience')) {
            $user->years_of_experience = $request->input('years_of_experience');
            $changes = true;
        }
        if ($request->input('track') && $user->track !== $request->input('track')) {
            $user->track = $request->input('track');
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
        if ($request->input('Address') && $user->Address !== $request->input('Address')) {
            $user->Address = $request->input('Address');
            $changes = true;
        }
        if ($request->input('date_of_birth') && $user->date_of_birth !== $request->input('date_of_birth')) {
            $user->date_of_birth = $request->input('date_of_birth');
            $changes = true;
        }
        if ($request->input('education') && $user->education !== $request->input('education')) {
            $user->education = $request->input('education');
            $changes = true;
        }
        if ($request->input('role') && $user->role !== $request->input('role')) {
            $user->role = $request->input('role');
            $changes = true;
        }
        if ($request->input('code') && $user->code !== $request->input('code')) {
            $user->code = $request->input('code');
            $changes = true;
        }
        if ($request->input('code_expired_at') && $user->code_expired_at !== $request->input('code_expired_at')) {
            $user->code_expired_at = $request->input('code_expired_at');
            $changes = true;
        }
        if ($request->input('email_verified_at') && $user->email_verified_at !== $request->input('email_verified_at')) {
            $user->email_verified_at = $request->input('email_verified_at');
            $changes = true;
        }
        if ($request->input('password') && $user->password !== $request->input('password')) {
            $user->password = $request->input('password');
            $changes = true;
        }
        if ($request->input('remember_token') && $user->remember_token !== $request->input('remember_token')) {
            $user->remember_token = $request->input('remember_token');
            $changes = true;
        }

        if ($request->input('imageUrl') && $user->imageUrl !== $request->input('imageUrl')) {
            $user->imageUrl = $request->input('imageUrl');
            $changes = true;
        }


        // Handle image upload, if provided
        if ($request->hasFile('file_path')) {
            $image = $request->file('file_path');
            $imagePath = $this->upload($image, 'users');
            $user->imageUrl = "images/users/$imagePath";
        }

        // Save the updated user profile to the database if there are changes
        if ($changes) {
            $user->save();
            return $this->successMessage('UserProfile updated successfully', 200,[]);
        }

        // No changes to update
        return $this->errorMessage([], 'No changes to update', 422);
    }





    public function destroyUser($id)
    {
    }
}
