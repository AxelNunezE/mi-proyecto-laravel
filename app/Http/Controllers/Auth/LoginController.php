<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $result = $this->authService->login($request->email, $request->password);

        if ($result['success']) {
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => $result['message']
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        return redirect('/login');
    }
}
