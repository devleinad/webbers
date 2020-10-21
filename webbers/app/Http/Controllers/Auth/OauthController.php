<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SocialAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite as Socialite;

class OauthController extends Controller
{
    public function handleProviderToRedirect($provider)
    {

        return Socialite::driver($provider)->redirect();
    }

    public function handleRedirectedProvider($provider)
    {
        $socialite_user = Socialite::driver($provider)->user();
        $is_social_account_user = SocialAccount::where('provider', '=', $provider)
            ->where('provider_id', '=', $socialite_user->getId())
            ->first();
        if ($is_social_account_user) {
            Auth::login(User::find($is_social_account_user->user_id));
            return redirect()->route('home');
        } else {
            //check for email availability
            $email_is_exists = User::where('email', '=', $socialite_user->getEmail())
                ->exists();
            if (!$email_is_exists) {
                $default_username = "user_" . rand(100000, 999999);
                $userIdentifier = rand(10000, 999999);
                $newUser = new User;
                $newUser->name = $socialite_user->getName();
                $newUser->email = $socialite_user->getEmail();
                $newUser->identifier = $userIdentifier;
                $newUser->username = $default_username;
                $newUser->user_type = "ordinary";
                $newUser->save();

                $newUser->social_accounts()->create([
                    'user_id' => $newUser->id,
                    'provider_id' => $socialite_user->getId(),
                    'provider' => $provider,
                ]);

                $newUser->profile()->create([
                    'avatar' => $socialite_user->getAvatar(),
                    'url' => env('APP_URL') . '/' . $default_username . '/profile/',
                    'bio' => '',
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                ]);
                //event(new CreateUserDirectoryEvent($newUser));
                Auth::login($newUser);
                //event(new OAuthenticated($newUser));
                return redirect()->route('home');
            } else {
                return redirect()->route('register')->with('error_duplicate', 'Sorry, this email is taken!');
            }
        }
    }
}