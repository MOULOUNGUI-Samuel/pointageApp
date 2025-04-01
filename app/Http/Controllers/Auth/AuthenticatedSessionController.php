<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.loginAdmin');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // $user = new User();
        // $user->nom = 'Doe';
        // $user->prenom = 'John';
        // $user->matricule ='123456';
        // $user->email = 'samuel@gmail.com';
        // $user->password = Hash::make('123456');
        // $user->date_naissance = '2000-01-01';
        // $user->fonction = 'Developpeur';
        // $user->role_user = 'Admin';
        // $user->save();

        try {
            $request->authenticate();
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->only('matricule'))->withErrors([
                'login' => 'Informations de connexion incorrect,merci de reessayer.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
