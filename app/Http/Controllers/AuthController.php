<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'প্রদত্ত ইমেইল বা পাসওয়ার্ড সঠিক নয়।',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'shop_owner',
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        // Auto-create shop for owner with 24 Hours Free Demo (20 test prints)
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => $request->shop_name,
            'slug' => Str::slug($request->shop_name) . '-' . Str::lower(Str::random(4)),
            'phone' => $request->phone,
            'bkash_number' => $request->phone,
            'nagad_number' => $request->phone,
            'rate_bw_single' => 3.00,
            'rate_bw_double' => 5.00,
            'rate_color_single' => 10.00,
            'rate_color_double' => 18.00,
            'rate_photo_passport' => 30.00,
            'api_token' => Str::random(40),
            'subscription_plan' => 'free_trial',
            'subscription_expires_at' => now()->addHours(24),
            'is_lifetime' => false,
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('shop.dashboard')->with('success', 'স্বাগতম! আপনার দোকানের অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('shop.dashboard');
    }
}
