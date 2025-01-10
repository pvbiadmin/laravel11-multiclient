<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function adminLogin()
    {
        return view('admin.login');
    }

    public function adminLoginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->all();

        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];

        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful');
        } else {
            return back()->with('error', 'Invalid credentials');
        }
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logout successful');
    }

    public function adminPasswordReset()
    {
        return view('admin.password_reset');
    }

    public function adminPasswordResetSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin_data = Admin::where('email', $request->email)->first();

        if (!$admin_data) {
            return redirect()->back()->with('error', 'Email not found');
        }

        try {
            DB::beginTransaction();

            // Add expiration time (60 minutes from now)
            $expiry = now()->addMinutes(60);

            // Generate token with expiry
            $token = hash('sha256', $admin_data->email . $expiry->timestamp . Str::random(40));

            // Update admin data
            $admin_data->update([
                'token' => $token,
                'token_expire_at' => $expiry
            ]);

            $reset_link = url('admin/reset-password/' . $token . '/' . $request->email);

            Mail::to($request->email)->send(new PasswordResetMail(
                'Reset Password Notification',
                $reset_link
            ));

            DB::commit();

            return redirect()->back()->with('success', 'Password reset link has been sent to your email');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    public function adminResetPasswordShow($token, $email)
    {
        $admin = Admin::where('email', $email)
            ->where('token', $token)
            ->where('token_expire_at', '>', now())
            ->first();

        if (!$admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Invalid or expired password reset link');
        }

        return view('admin.auth.reset_password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function adminResetPasswordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $admin_data = Admin::where('email', $request->email)
            ->where('token', $request->token)
            ->where('token_expire_at', '>', now())
            ->first();

        $admin_data->update([
            'password' => Hash::make($request->password),
            'token' => null,
            'token_expire_at' => null
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successful. You can now log in.');
    }
}
