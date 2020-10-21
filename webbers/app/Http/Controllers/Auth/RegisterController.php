<?php

namespace App\Http\Controllers\Auth;

use App\Events\CreateUserDirectoryEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(UserRegisterRequest $request)
    {
        return Validator::make($request, $request->rules());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        //avatars to be randomly picked
        $avatars = [
            'photos/default_avatars/default_1.svg',
            'photos/default_avatars/default_2.svg',
            'photos/default_avatars/default_3.svg',
            'photos/default_avatars/default_4.svg',
            'photos/default_avatars/default_5.svg',
            'photos/default_avatars/default_6.png',

        ];

        $default_avatar = $avatars[array_rand($avatars, 1)];
        $userIdentifier = rand(10000, 999999);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'user_type' => 'ordinary',
            'identifier' => $userIdentifier,
            'password' => Hash::make($data['password'], ['rounds' => 12,]),
        ]);


        if ($user) {
            $user->profile()->create([
                'avatar' => $default_avatar,
                'url' => env('APP_URL') . '/' . $user->username . '/profile/',
                'bio' => '',
                'twitter' => '',
                'facebook' => '',
                'instagram' => '',
            ]);

            //now create a profile directory for user
            // event(new CreateUserDirectoryEvent($user));
            return $user;
        }
    }
}