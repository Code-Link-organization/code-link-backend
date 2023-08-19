<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\CodeRequest;
use App\Mail\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $token = $request->header('Authorization');
        $code = rand(1000, 9999); // Generate a 4-digit code
        $user->code = $code;
        $user->code_expired_at = now()->addMinutes(config('auth.code_timeout'));
        $user->save();

        try {
            Mail::to($user)->send(new VerificationCode($user));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['mail' => $e->getMessage()], 'Please Try Again Later');
        }

        // Remove "Bearer" prefix from token
        $token = str_replace('Bearer ', '', $token);

        $user->token = $token;

        return ApiTrait::data(['data' => ['user' => $user]], "Mail Sent Successfully", 200);
    }

    public function verify(CodeRequest $request)
    {
        $user = Auth::guard('sanctum')->user();
        $token = $request->header('Authorization');
        $now = now();
        
        if ($user->code != $request->code) {
            return ApiTrait::errorMessage(['code' => 'Wrong Code'], "Invalid Code", 422);
        }
        
        if ($now > $user->code_expired_at) {
            return ApiTrait::errorMessage(['code' => 'Expired Code'], "Invalid Code", 422);
        }
        
        $user->email_verified_at = $now;
        $user->save();
        $token = str_replace('Bearer ', '', $token);

        $user->token = $token;
        
        return ApiTrait::data(['data' => ['user' => $user]], "Correct Code", 200);
    }
}


