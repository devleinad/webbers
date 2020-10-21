<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|min:6|unique:users|string',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please tell us your name!',
            'name.string' => 'Your name must comprise latin characters a-zA-Z0-9_- must not begin with a number! and must ',
            'email.required' => 'Please specify your email address!',
            'email.email' => 'Invalid email format!',
            'email.unque:users' => 'Sorry, this email address is taken!',
            'username.required' => 'You must choose a username!',
            'username.string' => 'Your username must comprise latin characters a-zA-Z0-9_- and must not begin with a number!',
            'username.min:6' => 'Your username must be at least 6 characters long!',
            'username.unique:users' => 'Sorry, this username belongs to another user!',
            'password.required' => 'Please choose a password!',
            'password.min:6' => 'Your password must be at least 6 characters long!',
            'password.confirmed' => 'Sorry, the two passwords do not match!',
        ];
    }
}