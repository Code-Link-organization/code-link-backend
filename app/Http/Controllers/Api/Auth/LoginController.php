<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiTrait::errorMessage(
                ['login' => __('Invalid email or password.')],
                __('Login failed. Please check your credentials.'),
                403
            );
        }

        $responseData = [
            'user' => $user->only($user->responseFields()),
        ];

        if (!$user->email_verified_at) {
            return ApiTrait::data(
                $responseData,
                __('Your account is not verified. Please verify your email.'),
                401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $responseData['user']['token'] = $token;

        return ApiTrait::data(
            $responseData,
            __('Login successful.'),
            200
        );
    }


    public function logout(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        // $user->currentAccessToken()->delete();
        return ApiTrait::successMessage(__('Logout successful.'));
    }
}
