<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'telefono' => 'nullable|string|max:20'
        ]);

        $result = $this->authService->register($request->all());

        if ($result['success']) {
            return redirect('/dashboard')->with('success', $result['message']);
        }

        return back()->withErrors([
            'email' => $result['message']
        ])->withInput();
    }
}
