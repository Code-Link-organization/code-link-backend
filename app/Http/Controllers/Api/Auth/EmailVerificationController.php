<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CodeRequest;
use App\Mail\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function sendEmail(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return ApiTrait::errorMessage([], 'User not found', 404);
        }

        $code = rand(1000, 9999); // Generate a 4-digit code
        $user->code = $code;
        $user->code_expired_at = now()->addMinutes(config('auth.code_timeout'));
        $user->save();

        try {
            Mail::to($user)->send(new VerificationCode($user));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['mail' => $e->getMessage()], 'Please Try Again Later');
        }

        $userData = $user->only($user->responseFields('email_verified_at')); // Exclude token
        return ApiTrait::data(['user' => $userData], "Mail Sent Successfully, You Will Receive Code In Your Email", 200);
    }

    public function verifyEmail(CodeRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return ApiTrait::errorMessage([], 'User not found', 404);
        }

        $now = now();

        if ($user->code != $request->code) {
            return ApiTrait::errorMessage(['code' => 'Wrong Code'], "Invalid Code", 422);
        }

        if ($now > $user->code_expired_at) {
            return ApiTrait::errorMessage(['code' => 'Expired Code'], "Invalid Code", 422);
        }

        $user->email_verified_at = $now;
        $user->save();

        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user data and token
        $userData = $user->only($user->responseFields());
        $userData['token'] = $token;

        return ApiTrait::data(['user' => $userData], "Email address has been verified successfully", 200);
    }
}
