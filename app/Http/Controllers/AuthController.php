<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/admin/dashboard');
    }

    /**
     * Redirect to Xelenic OAuth provider
     */
    public function redirectToXelenic()
    {
        return Socialite::driver('xelenic')->redirect();
    }

    /**
     * Handle Xelenic OAuth callback
     */
    public function handleXelenicCallback(Request $request)
    {
        try {
            // Check if we have the required parameters
            if (!$request->has('code')) {
                throw new \Exception('Authorization code not received from Xelenic');
            }

            $xelenicUser = Socialite::driver('xelenic')->user();
            
            // Check if user already exists
            $user = User::where('email', $xelenicUser->getEmail())->first();
            
            if ($user) {
                // Update existing user with Xelenic data
                $user->update([
                    'name' => $xelenicUser->getName(),
                    'xelenic_id' => $xelenicUser->getId(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $xelenicUser->getName(),
                    'email' => $xelenicUser->getEmail(),
                    'password' => Hash::make(uniqid()), // Random password since OAuth
                    'xelenic_id' => $xelenicUser->getId(),
                ]);
            }
            
            Auth::login($user);
            
            return redirect('/admin/dashboard');
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Handle state mismatch - redirect to login with error
            return redirect('/login')->withErrors([
                'email' => 'OAuth state mismatch. Please try logging in again.',
            ]);
        } catch (\Exception $e) {

            // Log the error for debugging
            \Log::error('OAuth Authentication Error: ' . $e->getMessage());
            \Log::error('OAuth Error Details: ' . $e->getTraceAsString());
            
            return redirect('/login')->withErrors([
                'email' => 'OAuth authentication failed: ' . $e->getMessage(),
            ]);
        }
    }
}
