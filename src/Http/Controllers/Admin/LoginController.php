<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Acme\CmsDashboard\Models\BlockedIp;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Check IP Block
        if (BlockedIp::where('ip_address', $request->ip())->where('attempts', '>=', 5)->exists()) {
            abort(403, 'You do not have permission to access this page. Your IP has been blocked.');
        }

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
        // Check IP Block
        if (BlockedIp::where('ip_address', $request->ip())->where('attempts', '>=', 5)->exists()) {
            abort(403, 'You do not have permission to access this page. Your IP has been blocked.');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check manual permanent block
            if ($user->is_blocked && !$user->blocked_until) {
                return back()->withErrors(['email' => 'Your account has been permanently blocked.'])->onlyInput('email');
            }

            // Check temporary block
            if ($user->blocked_until) {
                if ($user->blocked_until->isFuture()) {
                    $diffInSeconds = now()->diffInSeconds($user->blocked_until);
                    if ($diffInSeconds > 0) {
                        $minutes = ceil($diffInSeconds / 60);
                        return back()->withErrors([
                            'email' => "Too many failed attempts. Your account is temporarily blocked. Please try again after {$minutes} minutes."
                        ])->onlyInput('email');
                    }
                } else {
                    // Block expired, reset attempts but keep log
                    $user->update(['login_attempts' => 0, 'blocked_until' => null]);
                }
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Re-check block just in case session/auth mismatch
            if ($user->is_blocked || ($user->blocked_until && $user->blocked_until->isFuture())) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is restricted.'])->onlyInput('email');
            }

            // Reset attempts on successful login
            $user->update([
                'login_attempts' => 0,
                'blocked_until' => null,
                'last_failed_login_ip' => null
            ]);

            // Clear IP attempts if any
            BlockedIp::where('ip_address', $request->ip())->delete();
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard.index'));
        }

        // On Failure
        if ($user) {
            $user->increment('login_attempts');
            $attemptsLeft = 5 - $user->login_attempts;
            
            if ($user->login_attempts >= 5) {
                $user->update([
                    'blocked_until' => now()->addMinutes(30),
                    'last_failed_login_ip' => $request->ip()
                ]);
                return back()->withErrors(['email' => 'Too many failed attempts. Your account and IP have been blocked for 30 minutes.'])->onlyInput('email');
            }

            return back()->withErrors([
                'email' => "Invalid credentials. You have {$attemptsLeft} attempts left before your account is blocked.",
            ])->onlyInput('email');
        } else {
            // Unregistered user attempt
            $ipRecord = BlockedIp::firstOrCreate(['ip_address' => $request->ip()]);
            $ipRecord->increment('attempts');
            
            if ($ipRecord->attempts >= 5) {
                $geoData = $this->getCountryFromIp($request->ip());
                $ipRecord->update([
                    'reason' => 'Too many attempts with non-existent emails',
                    'country' => $geoData['name'],
                    'country_code' => $geoData['code'],
                ]);
                abort(403, 'Too many failed attempts. Your IP has been permanently blocked.');
            }

            $attemptsLeft = 5 - $ipRecord->attempts;
            return back()->withErrors([
                'email' => "Invalid credentials. You have {$attemptsLeft} attempts left before your IP is blocked.",
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function getCountryFromIp($ip)
    {
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}");
            if ($response) {
                $data = json_decode($response, true);
                return [
                    'name' => $data['country'] ?? 'Unknown',
                    'code' => isset($data['countryCode']) ? strtolower($data['countryCode']) : null
                ];
            }
        } catch (\Exception $e) {}
        return ['name' => 'Unknown', 'code' => null];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
