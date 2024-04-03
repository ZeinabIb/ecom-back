<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function callback($provider)
    {
        {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'username' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => null,
                    'user_type' => 'default',
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'email_verified_at' => now(),
                    'phone' => null,
                ]
            );

            return redirect('http://localhost:3000/landing?username='.$user->username.'&email='.$user->email.'&user_type='.$user->user_type.'&phone='.$user->phone);


        }
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $updated = DB::table('users')
        ->where('username', $request->name)
        ->where('email', $request->email)
            ->update(['phone' => $request->phone]);

        if ($updated) {
            return response()->json(['message' => 'User phone number updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'User not found or data unchanged.'], 404);
        }
    }


}
