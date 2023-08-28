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
