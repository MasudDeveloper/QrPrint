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

    public function showRegisterForm(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $selectedType = $request->query('type', 'shop');
        if (!in_array($selectedType, ['shop', 'office'])) {
            $selectedType = 'shop';
        }
        return view('auth.register', compact('selectedType'));
    }

    public function register(Request $request)
    {
        $accountType = $request->input('account_type', 'shop');
        $isOffice = ($accountType === 'office');

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'account_type' => 'nullable|string|in:shop,office',
        ];

        if ($isOffice) {
            $rules['office_name'] = 'required_without:shop_name|string|max:255';
        } else {
            $rules['shop_name'] = 'required_without:office_name|string|max:255';
        }

        $request->validate($rules, [
            'shop_name.required_without' => 'দোকানের নাম দেওয়া আবশ্যক।',
            'office_name.required_without' => 'প্রতিষ্ঠানের নাম দেওয়া আবশ্যক।',
            'email.unique' => 'এই ইমেইল দিয়ে ইতোমধ্যে একটি অ্যাকাউন্ট রয়েছে।',
        ]);

        $displayName = $isOffice 
            ? ($request->office_name ?: $request->shop_name) 
            : ($request->shop_name ?: $request->office_name);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'shop_owner',
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        // Auto-create shop / office entity
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => $displayName,
            'slug' => Str::slug($displayName) . '-' . Str::lower(Str::random(4)),
            'phone' => $request->phone,
            'bkash_number' => $request->phone,
            'nagad_number' => $request->phone,
            'rate_bw_single' => $isOffice ? 0.00 : 3.00,
            'rate_bw_double' => $isOffice ? 0.00 : 5.00,
            'rate_color_single' => $isOffice ? 0.00 : 10.00,
            'rate_color_double' => $isOffice ? 0.00 : 18.00,
            'rate_photo_passport' => $isOffice ? 0.00 : 30.00,
            'api_token' => Str::random(40),
            'subscription_plan' => $isOffice ? 'office_trial' : 'free_trial',
            'subscription_expires_at' => $isOffice ? now()->addDays(7) : now()->addHours(24),
            'is_office_mode' => $isOffice,
            'is_lifetime' => false,
            'is_active' => true,
        ]);

        Auth::login($user);

        $welcomeMsg = $isOffice
            ? 'স্বাগতম! আপনার অফিসের সেন্ট্রাল প্রিন্ট নেটওয়ার্ক সফলভাবে চালু হয়েছে।'
            : 'স্বাগতম! আপনার দোকানের অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।';

        return redirect()->route('shop.dashboard')->with('success', $welcomeMsg);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('status', 'আপনার ইমেইলে পাসওয়ার্ড রিসেট করার লিঙ্ক সফলভাবে পাঠানো হয়েছে!')
            : back()->withErrors(['email' => 'এই ইমেইল দিয়ে কোনো অ্যাকাউন্ট পাওয়া যায়নি।']);
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                Auth::login($user);
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!')
            : back()->withErrors(['email' => 'পাসওয়ার্ড রিসেট টোকেনটি সঠিক নয় বা মেয়াদ উত্তীর্ণ হয়েছে।']);
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('shop.dashboard');
    }
}
