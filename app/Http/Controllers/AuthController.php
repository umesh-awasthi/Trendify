<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Customer;

class AuthController extends Controller
{
    // Show login form (for web)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login (for web)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try admin login first
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Then try agent login
        
        if (Auth::guard('web')->attempt($credentials) && Auth::user()->isAgent()) {
            $request->session()->regenerate();
            return redirect()->route('agent.dashboard');
        }

        // Then try customer login
        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show registration form (for web)
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration (for web)
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('login');
    }

    // Handle logout (for web)
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        if (Auth::guard('web')->check() && Auth::user()->isAgent()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // API: Register a new customer
    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Customer registered successfully',
            'customer' => $customer,
        ], 201);
    }

    // API: Authenticate and login user (customer or agent)
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try customer login first
        if (Auth::guard('customer')->attempt($credentials)) {
            $user = Auth::guard('customer')->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Try agent login
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            if ($user->role !== 'agent') {
                Auth::guard('web')->logout();
                return response()->json([
                    'message' => 'Unauthorized access'
                ], 403);
            }
            $token = $user->createToken('agent-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }


   
    // API: Logout user (customer or agent)
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // API: Send password reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if email exists in either customers or users table
        $existsInCustomers = DB::table('customers')->where('email', $request->email)->exists();
        $existsInUsers = DB::table('users')->where('email', $request->email)->exists();

        if (!$existsInCustomers && !$existsInUsers) {
            return response()->json([
                'message' => 'Email not found in our records'
            ], 404);
        }

        // Generate and store reset token
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Generate and log the reset link
        $resetLink = url('/password/reset?token='.$token);
        Log::info('Password reset link generated for '.$request->email.': '.$resetLink);

        return response()->json([
            'message' => 'Password reset link generated successfully.',
            'reset_link' => $resetLink,
            'instructions' => 'Use this link to reset your password. The link will expire in 60 minutes.'
        ]);
    }

    // API: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        // Update password in appropriate table
        if (DB::table('customers')->where('email', $request->email)->exists()) {
            Customer::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);
        } else {
            User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);
        }

        // Delete used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }

}
