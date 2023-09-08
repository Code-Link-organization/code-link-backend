<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;

class UserController extends Controller
{
    use ApiTrait;

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
            'imageUrl' => $user->imageUrl
        ];

        return $this->data(['user' => $userData], 'User retrieved successfully', 200);
    }


   
    public function editUser(Request $request, $id)
    {
    }

    
   

   
    public function destroyUser($id)
    {
      
    }
}
