<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function register(Request $request)
{
    Log::info('Register function called');

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed', ['errors' => $validator->errors()]);
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $otp = rand(100000, 999999); // Generate a random OTP
    Log::info('Generated OTP', ['otp' => $otp]);

    // Store user data in the session temporarily
    session([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_otp' => $otp
    ]);

    // Send OTP to the user's email
    try {
        Mail::to($request->email)->send(new SendOtpMail($otp));
        Log::info('OTP email sent', ['email' => $request->email]);
    } catch (\Exception $e) {
        Log::error('Failed to send OTP email', ['error' => $e->getMessage()]);
    }

    return redirect()->route('verify.email.otp.form');
}

    public function showEmailOtpForm()
    {
        return view('admin.authentication.verify-email');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == session('email_otp')) {
            // Remove the email OTP from the session
            session()->forget('email_otp');

            return redirect()->route('whatsapp.number.form');
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    public function showWhatsAppNumberForm()
    {
        return view('admin.authentication.whatsapp-form');
    }

    public function sendWhatsAppOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $otp = rand(100000, 999999); // Generate a random OTP

        // Store WhatsApp OTP and number in the session
        session([
            'phone' => $request->phone,
            'whatsapp_otp' => $otp
        ]);

        // Send OTP to the user's WhatsApp
        $this->sendWhatsAppMessage($request->phone, $otp);

        return redirect()->route('verify.whatsapp.otp.form');
    }

    public function showWhatsAppOtpForm()
    {
        return view('admin.authentication.whatsapp-verify');
    }

    public function verifyWhatsAppOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == session('whatsapp_otp')) {
            // Create the user
            User::create([
                'name' => session('name'),
                'email' => session('email'),
                'password' => session('password'),
                'phone' => session('phone'),
                'phone_verified' => 'Yes'
            ]);

            // Clear session data
            session()->forget(['name', 'email', 'password', 'phone', 'whatsapp_otp']);

            return redirect()->route('login')->with('success', 'Account created successfully. You can now log in.');
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    public function sendWhatsAppMessage($recipientNumber, $otp)
    {
        $twilioSid = config('app.twilio_sid');
        $twilioToken = config('app.twilio_auth_token');
        $twilioWhatsAppNumber = config('app.twilio_whatsapp_number');
        $recipientNumber = 'phone:' . $recipientNumber;
        $message = "Your OTP for account verification is: $otp";

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            $twilio->messages->create(
                $recipientNumber,
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function resendEmailOtp()
    {
        $otp = rand(100000, 999999); // Generate a new OTP

        // Update OTP in the session
        session(['email_otp' => $otp]);

        // Resend OTP to the user's email
        Mail::to(session('email'))->send(new \App\Mail\SendOtpMail($otp));

        return redirect()->back()->with('success', 'OTP resent successfully.');
    }

    public function resendWhatsAppOtp()
    {
        $otp = rand(100000, 999999); // Generate a new OTP

        // Update OTP in the session
        session(['whatsapp_otp' => $otp]);

        // Resend OTP to the user's WhatsApp
        $this->sendWhatsAppMessage(session('whatsapp_number'), $otp);

        return redirect()->back()->with('success', 'OTP resent successfully.');
    }
}