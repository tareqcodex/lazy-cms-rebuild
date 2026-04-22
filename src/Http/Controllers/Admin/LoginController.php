<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard.index');
        }

        $theme = get_cms_option('login_theme', 'classic');

        if ($theme === 'funny') {
            return view('cms-dashboard::admin.auth.login-funny');
        }

        if ($theme === 'breeze') {
            return view('cms-dashboard::admin.auth.login-modern');
        }

        if ($theme === 'wp') {
            return view('cms-dashboard::admin.auth.login-wp');
        }

        return view('cms-dashboard::admin.login');
    }

    public function checkCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $isValid = Auth::validate($credentials);

        return response()->json([
            'valid' => $isValid,
            'email_exists' => User::where('email', $request->email)->exists()
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard.index'));
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
        return redirect()->route('admin.login');
    }
}
