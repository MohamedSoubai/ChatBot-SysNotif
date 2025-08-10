<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mail;
use Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }
  
    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ])->validate();
    
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'level'    => 'Admin',
        ]);
    
        Mail::to($user->email)->queue(new WelcomeUserMail($user));
    
        return redirect()->route('login')
                         ->with('success', 'Votre compte a été créé ! Un e-mail de bienvenue vous a été envoyé.');
    }
  
    public function login()
    {
        return view('auth/login');
    }
  
    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();
  
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }
  
        $request->session()->regenerate();
  
        return redirect()->route('dashboard');
    }
  
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/');
    }
 
    public function profile()
    {
        return view('profile');
    }
}
