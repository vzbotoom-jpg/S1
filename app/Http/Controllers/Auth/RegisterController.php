<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     */
    protected string $redirectTo = '/dashboard';
    
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // HAPUS baris ini karena middleware sudah diatur di routes/web.php
        // $this->middleware('guest');
    }
    
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }
    
    /**
     * Handle a registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
            'preferences' => json_encode([
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'default_model' => 'gemini-pro'
            ]),
        ]);
        
        event(new Registered($user));
        
        Auth::login($user);
        
        return redirect($this->redirectTo);
    }
}