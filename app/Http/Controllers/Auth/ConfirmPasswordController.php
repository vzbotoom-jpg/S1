<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\View\View;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    protected string $redirectTo = '/chat';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showConfirmForm(): View
    {
        return view('auth.passwords.confirm');
    }
}