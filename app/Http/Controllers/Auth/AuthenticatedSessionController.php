<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('index');
    }
    //redirect to google
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    
    public function  handleGoogleCallback(){
        $user = Socialite::driver('google')->stateless()->user();
        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser){
            Auth::login($existingUser, true);
            return redirect()->route('index');
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($newUser, true);
            return redirect()->route('index');
        }
    }
    
            public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();

    }

    public function  handleFacebookCallback(){
        $user = Socialite::driver('facebook')->user();
        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser){
            Auth::login($existingUser, true);
            return redirect()->route('index');
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($newUser, true);
            return redirect()->route('index');
        }
    }

}
  