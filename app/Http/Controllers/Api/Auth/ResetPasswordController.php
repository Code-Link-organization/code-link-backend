<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode;

class ResetPasswordController extends Controller
{
//     public function checkEmail(CheckEmailRequest $request)
// {
//     $user = User::where('email', $request->email)->first();

//     if (!$user) {
//         return ApiTrait::errorMessage(['email' => 'Incorrect Email'], 'User Not Found', 400);
//     }

//     // Generate a verification code
//     $code = rand(1000, 9999); // Generate a 4-digit code
//     $user->code = $code;
//     $user->code_expired_at = now()->addMinutes(config('auth.code_timeout'));
//     $user->save();

//     // Send the verification email
//     try {
//         Mail::to($user)->send(new VerificationCode($user));
//     } catch (\Exception $e) {
//         return ApiTrait::errorMessage(['mail' => $e->getMessage()], 'Please Try Again Later');
//     }

//     return ApiTrait::data(
//         ['user' => $user->only($user->responseFields('email_verified_at'))],
//         'Verification Code Sent Successfully',
//         200
//     );
// }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->safe()->except('password_confirmation', 'password');
        $data['password'] = Hash::make($request->password);

        try {
            $user = Auth::guard('sanctum')->user();
            $user->update($data);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([], "Something went wrong", 500);
        }

        $userData = $user->only($user->responseFields());

        return ApiTrait::data(
            ['user' => $userData],
            'Password Reset Successfully', 200);
    }
}
