<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\SignupRequest;

class SignupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SignupRequest $request)
    {
        $data = $request->safe()->except('password_confirmation', 'password');
        $data['password'] = Hash::make($request->password);

        try {
            $user = User::create($data);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([], "Something went wrong", 500);
        }

        $userData = $user->only($user->responseFields());

        return response()->json([
            'result' => true,
            'message' => "Registration Successful, now verify your email",
            'data' => [
                'user' => $userData,
            ],
            'errors' => [],
        ], 201);
    }
}

