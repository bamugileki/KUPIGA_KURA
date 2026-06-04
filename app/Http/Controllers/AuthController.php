<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\SuspiciousLog;
use App\Services\NidaService;
use App\Services\FraudDetectionService;
use App\Rules\ValidNidaFormat;
use App\Rules\UniqueIdentity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    protected NidaService $nidaService;
    protected FraudDetectionService $fraudService;

    public function __construct(NidaService $nidaService, FraudDetectionService $fraudService)
    {
        $this->nidaService = $nidaService;
        $this->fraudService = $fraudService;
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'nida_number' => 'nullable|string|size:25|unique:users,nida_number',
            'driving_licence' => 'nullable|string|max:30|unique:users,driving_licence',
            'nhif_number' => 'nullable|string|max:20|unique:users,nhif_number',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'nida_number' => $request->nida_number,
            'driving_licence' => $request->driving_licence,
            'nhif_number' => $request->nhif_number,
            'role' => 'voter',
            'is_voter' => true,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'USER_REGISTERED',
            'details' => "User {$user->full_name} registered as a voter",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for account verification.');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGOUT',
                'details' => 'User logged out',
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function setLanguage($lang)
    {
        if (in_array($lang, ['en', 'sw'])) {
            session(['lang' => $lang]);
            if (Auth::check()) {
                $user = Auth::user();
                $user->language = $lang;
                $user->save();
            }
        }
        return redirect()->back();
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $ipAddress = $request->ip();

        $user = User::where('nida_number', $identifier)
            ->orWhere('driving_licence', $identifier)
            ->orWhere('nhif_number', $identifier)
            ->first();

        if (!$user) {
            AuditLog::create([
                'user_id' => null,
                'action' => 'LOGIN_FAILED',
                'details' => "Failed login attempt with identifier: {$identifier}",
                'ip_address' => $ipAddress,
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);
            return back()->withErrors(['identifier' => 'Invalid credentials. Please try again.'])->withInput();
        }

        if ($user->isLocked()) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'LOGIN_BLOCKED',
                'details' => 'Account is locked due to too many failed attempts',
                'ip_address' => $ipAddress,
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);
            return back()->withErrors(['identifier' => 'Account is locked due to too many failed login attempts. Please try again later.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            $user->failed_attempts += 1;
            if ($user->failed_attempts >= config('voting.max_failed_attempts', 3)) {
                $lockMinutes = config('voting.lock_minutes_base', 30)
                    * pow(config('voting.lock_multiplier', 2), $user->failed_attempts - 3);
                $user->locked_until = Carbon::now()->addMinutes($lockMinutes);
                $user->save();
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'ACCOUNT_LOCKED',
                    'details' => "Account locked for {$lockMinutes} minutes",
                    'ip_address' => $ipAddress,
                    'device_info' => $request->userAgent(),
                    'timestamp' => Carbon::now(),
                ]);
                $this->fraudService->flag('Repeated failed login attempts', $user, $ipAddress);
                return back()->withErrors(['identifier' => 'Account is locked due to too many failed login attempts. Please try again later.'])->withInput();
            }
            $user->save();
            return back()->withErrors(['identifier' => 'Invalid credentials. Please try again.'])->withInput();
        }

        if ($user->status === 'pending') {
            return back()->withErrors(['identifier' => 'Your account is pending approval from the election commission.'])->withInput();
        }
        if ($user->status === 'rejected') {
            return back()->withErrors(['identifier' => 'Your account has been rejected. Contact the election commission.'])->withInput();
        }

        $user->failed_attempts = 0;
        $user->locked_until = null;
        $user->save();

        Auth::login($user, $request->boolean('remember'));

        session(['lang' => $user->language ?? config('voting.default_language', 'en')]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'LOGIN_SUCCESS',
            'details' => "User {$user->full_name} logged in successfully",
            'ip_address' => $ipAddress,
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        if ($user->isAdmin() || $user->isOfficer()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
