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
use Illuminate\Support\Facades\Password;

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

    // Show Admin Login Form
    public function showAdminLoginForm()
    {
        return view('auth.admin_login');
    }

    // Handle Admin Login
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show Customer Login Form
    public function showCustomerLoginForm()
    {
        return view('auth.customer_login');
    }

    // Handle Customer Login
    public function customerLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show Agent Login Form
    public function showAgentLoginForm()
    {
        return view('auth.agent_login');
    }

    // Handle Agent Login
    public function agentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials) && Auth::user()->isAgent()) {
            $request->session()->regenerate();
            return redirect()->route('agent.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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

    //  Send password reset link for admin
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
        // Log::info('Password reset link generated for '.$request->email.': '.$resetLink);

        // Redirect to the password reset view
        // Token is handled internally, no need to pass it in the redirect
        return redirect()->route('admin.password.request');
    }
 // api Send password reset link
    public function apisendResetLinkEmail(Request $request)
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
    public function apiresetPassword(Request $request)
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






    // admin: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return "Invalid email or token";
        }

        // Update password in the Admin table
        Admin::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return "Password reset successfully";
       
    }

    // Admin Password Reset Functionality
    public function showAdminResetForm()
    {
        return view('auth.admin_password_reset');
    }

    public function sendAdminResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:customers,email']);

        // Generate and store reset token
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Log the reset link generation
        // Log::info('Password reset link generated for '.$request->email);

        // Redirect to the password reset request view
        return redirect()->route('admin.password.reset', ['token' => $token]);
    }

    public function showAdminResetFormWithToken($token)
    {
        return view('auth.password_reset', ['token' => $token]);
    }

    public function adminReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return "Invalid email or token" ;
        }

        // Update password in the Admin table
        Admin::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

            return "Password reset successfully.";

    }

    // Customer Password Reset
    public function showCustomerResetForm()
    {
        return view('auth.customer_password_reset');
    }

    public function sendCustomerResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if email exists in the customers table
        $existsInCustomers = Customer::where('email', $request->email)->exists();

        if (!$existsInCustomers) {
            return "Email not found in our records";
            
        }

        // Generate and store reset token
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Generate and log the reset link
        $resetLink = url('/customer/password/reset?token='.$token);
        // Log::info('Password reset link generated for '.$request->email.': '.$resetLink);

        // Redirect to the password reset view
        return redirect()->route('customer.password.reset', ['token' => $token]);
    }

    public function showCustomerResetFormWithToken($token)
    {
        return view('auth.custopassword_reset', ['token' => $token]);
    }

    public function customerReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return "Invalid email or token";
        }

        // Update password in the Customer table
        Customer::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }

    // Agent Password Reset
    public function showAgentResetForm()
    {
        return view('auth.agent_password_reset');
    }

    public function sendAgentResetLinkEmail(Request $request)
   
    {
        $request->validate(['email' => 'required|email']);
    
        // Check if email exists in the users table
        $existsInUsers = User::where('email', $request->email)->exists();
    
        if (!$existsInUsers) {
            return "Email not found in our records";
       
        }
    
        // Generate and store reset token
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );
    
        // Generate and log the reset link
        $resetLink = url('/agent/password/reset?token='.$token);
    //    Log::info('Password reset link generated for '.$request->email.': '.$resetLink);
    
        // Redirect to the password reset view
        return redirect()->route('agent.password.reset', ['token' => $token]);
    }

    public function showAgentResetFormWithToken($token)
    {
        return view('auth.agentpassword_reset', ['token' => $token]);
    }

    public function agentReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return "Invalid email or token";
        }

        // Update password in the Customer table
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }

    
   
}
