<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\View\View;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected string $redirectTo = '/chat';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(): View
    {
        return view('auth.verify');
    }
}