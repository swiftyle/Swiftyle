<?php
namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailOtp;
use App\Mail\ResetPassword;
class AuthenticationController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.authentication.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            Session::put('name', $user->name);
            Session::put('username', $user->username);
            Session::put('email', $user->email);
            Session::put('phone', $user->phone);
            Session::put('gender', $user->gender);
            Session::put('password', $user->password);
            Session::put('role', $user->role);
            Session::put('avatar', $user->avatar);

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function showRegistrationForm()
    {
        return view('admin.authentication.sign-up');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
        ]);

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
            'email_otp' => $otp,
            'email_verified'=>'No'
        ]);

        Mail::to($user->email)->send(new EmailOtp($otp));

        return redirect()->route('verify.email.otp.form')->with('success', 'Registration successful. Please check your email for the OTP.');
    }

    public function showVerifyOtpForm()
    {
        return view('admin.authentication.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email_otp', $request->otp)->first();

        if ($user) {
            $user->email_verified = true;
            $user->email_verified_at = now();
            $user->email_otp = null;
            $user->email_verified ='Yes';
            $user->save();

            return redirect()->route('login')->with('success', 'Email verified successfully. Please login.');
        } else {
            return back()->withErrors(['error' => 'Invalid OTP or email.']);
        }
    }
    


    public function showForgetPasswordForm()
    {
        $data['title'] = 'Forget Password';
        return view('admin.authentication.forget-password', $data);
    }

    /**
     * Handle forget password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Update user's email_otp field
        $user->email_otp = $otp;
        $user->save();

        // Send email with OTP
        Mail::to($user->email)->send(new ResetPassword($otp));

        return redirect()->route('reset-password')->with('success', 'An OTP has been sent to your email for password reset.');
    }


    /**
     * Show the reset password form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm()
    {
        $data['title'] = 'Reset Password';
        return view('admin.authentication.reset-password', $data);
    }

    /**
     * Handle reset password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $user = User::where('email_otp', $request->otp)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // Reset password
        $user->password = Hash::make($request->password);
        $user->email_otp = null; // Clear OTP after reset
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login with your new password.');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
