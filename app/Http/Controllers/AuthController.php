<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function redirect($provider, Request $request)
    {
        session(['userType' => $request->query('userType')]);
        Cache::put('temp_user_type_', $request->query('userType'), now()->addMinutes(10));
        \Log::debug('Setting userType in session', ['userType' => $request->query('userType')]);
        return Socialite::driver($provider)->redirect();
    }


    public function callback($provider)
    {
        {
            \Log::debug('Retrieved userType from session', ['userType' => session('userType')]);
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $userType = session('userType', Cache::get('temp_user_type_', 'defaultType'));

            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'username' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => null,
                    'user_type' => $userType,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'email_verified_at' => now(),
                    'phone' => null,
                ]
            );

            session()->forget('userType');
            Cache::forget('userType');
            session()->forget('userType');
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return redirect('http://localhost:3000/landing?username='.$user->username.'&email='.$user->email.'&user_type='.$user->user_type.'&phone='.$user->phone.'&token='.$token);



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
