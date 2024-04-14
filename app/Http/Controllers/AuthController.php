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
use Exception;

class AuthController extends Controller
{
    public function redirect($provider, Request $request)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function callback($provider)
    {
        {
            try {
                \Log::info("Starting callback for provider: {$provider}");

                $user = Socialite::driver($provider)->stateless()->user();
                \Log::info("Received user from {$provider}: ", (array)$user);
                $providerIdColumn = $provider == 'google' ? 'google_id' : 'microsoft_id';

                $findUser = User::where($providerIdColumn, $user->id)->first();

                if($findUser)


                {  \Log::info("Found existing user for {$provider}"); Auth::login($findUser);}

                else
                {
                    \Log::info("Creating new user for {$provider}");
                    $newUser = User::create([
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        $providerIdColumn => $user->getId(),
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($newUser);
                }
                return redirect('/');

            } catch (Exception $e) {
                \Log::error("Error in {$provider} callback: " . $e->getMessage());
                dd($e->getMessage());
            }



        }
    }



//    public function updatePhone(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string',
//            'email' => 'required|email',
//            'phone' => 'required|string',
//        ]);
//
//        $updated = DB::table('users')
//        ->where('username', $request->name)
//        ->where('email', $request->email)
//            ->update(['phone' => $request->phone]);
//
//        if ($updated) {
//            return response()->json(['message' => 'User phone number updated successfully.'], 200);
//        } else {
//            return response()->json(['message' => 'User not found or data unchanged.'], 404);
//        }
//    }


}
