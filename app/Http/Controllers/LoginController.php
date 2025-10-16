<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index() {
        return view('supper-admin.auth.login');
    }

    function login(Request $request): RedirectResponse
    {
    $credentials = $request->validate([
        'email' => ['required'],
        'password' => ['required'],
    ]);
    $remember = $request->has('remember');
    // Attempt authentication using the 'web' guard
    if (Auth::guard('web')->attempt($credentials, $remember)) {
        $request->session()->regenerate();     
        return redirect()->intended('dashboard');
    }
    return back()->withErrors([ 'email' => 'The provided credentials do not match our records.', ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
    Auth::logout();
    $request->session()->invalidate();     
    $request->session()->regenerateToken();     
    return redirect('/');
    }

    public function forgotpassword() {
        return view('supper-admin.auth.forgot-password');
    }

    public function createForgetPasswordToken(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
        ]);
        $admin = User::where('email',$request->email)->first();
        $hash = bin2hex(random_bytes(32));
        $url = route('password-validation', ['email'  => $request->email, 'hash' => $hash]);
        if($admin){
            $admin->update([ 'reset_token' => $hash ]);
            $content = $this->forgetPasswordTemplate($request->email, $admin->name,$url);
            sendMailCurl($request->email, 'Reset Password', $content, $admin->name, 'SBS Admin' , 'sdsdevelopers1@gmail.com'); 
            return redirect()->back()->with('success' , 'Please check your email.');
        }
        return redirect()->back()->with('error' , 'Email address not found. Please check and try again.');

    }

    function forgetPasswordTemplate($email, $name,$link){
        $html = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Forget Password</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f6f6f6;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 20px 0; background-color: #f6f6f6;">
                            <tr>
                                <td align="center">
                                    <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1px solid #dddddd; padding: 20px;">
                                        <tr>
                                            <td align="center" style="padding: 10px 0;">
                                                <h1 style="font-size: 24px; margin: 0; color: #333333;">Reset Your Password</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px 0; color: #333333; font-size: 16px; line-height: 1.5;">
                                                <p style="margin: 0 0 10px;">Hello '.$name.',</p>
                                                <p style="margin: 0 0 10px;">You have requested to reset your password. Click the link below to reset it:</p>
                                                <p style="margin: 0 0 20px;"><a href="'.$link.'" style="color: #1a73e8; text-decoration: none;cursor:pointer;">Reset Password</a></p>
                                                <p style="margin: 0 0 10px;">If you didn\'t request this, please ignore this email.</p>
                                                <p style="margin: 0;">Thank you,</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding: 10px 0; border-top: 1px solid #dddddd;">
                                                <p style="margin: 0; color: #888888; font-size: 12px;">&copy; '.date("Y").' Efoam. All rights reserved.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>
        ';
        return $html;
    }
    
    function CheckHashPassword($hash , $email){
        $admin = User::where('email', $email)->where('reset_token',$hash)->first();
        if($admin){
            return view('supper-admin.auth.new-password')->with(['email' =>$email, 'hash' => $hash]);
        }
        return redirect()->route('forgotpassword')->with('error' , 'Invalid link! Regenerate password reset link');
    }

    function updatePassword(Request $request){
        if($request->password != $request->confirmPassword){
            return redirect()->back()->with('error','Password and Confirm Password do not match!');
        }
        $password = Hash::make($request->password);
        $admin = User::where('email',$request->emailCheck)->first();
        if($admin){
            $admin->update(['reset_token' => '' , 'password' => $password ]);
            return redirect()->route('login')->with('success','Password updated successfully! Login with new password');
        }
        return redirect()->route('login');
    }

    public function enterCode() {
        return view('supper-admin.auth.forgot-password-code');
    }

    public function forgotpasswordSuccess() {
        return view('supper-admin.auth.forgot-password-success');
    }
}
