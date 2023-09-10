<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'track' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'email_profile' => 'nullable|string|email|max:255',
            'cvUrl' => 'nullable|string||min:5|max:255',
            'githubUrl' => 'nullable|string||min:5|max:255',
            'linkedinUrl' => 'nullable|string||min:5|max:255',
            'behanceUrl' => 'nullable|string||min:5|max:255',
            'facebookUrl' => 'nullable|string||min:5|max:255',
            'twitterUrl' => 'nullable|string|min:5|max:255',
        ];
    }
}
