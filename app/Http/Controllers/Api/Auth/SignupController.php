<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignupRequest;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SignupController extends Controller
{
    use ApiTrait;
    
    public function __invoke(SignupRequest $request)
    {
        $data = $request->safe()->except('password_confirmation', 'password');
        $data['password'] = Hash::make($request->password);

        try {
            $user = User::create($data);

            // Send verification code
            $code = rand(1000, 9999); // Generate a 4-digit code
            $user->code = $code;
            $user->code_expired_at = now()->addMinutes(config('auth.code_timeout'));
            $user->save();

            Mail::to($user)->send(new VerificationCode($user));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([], "Something went wrong", 500);
        }

        $userData = $user->only($user->responseFields());

        return $this->data(['user' => $userData], 'Registration Successful, now verify your email', 200);
    }
}


