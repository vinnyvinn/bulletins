<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('home');
    }

    protected function authenticated(Request $request, $user)
    {
        if (! $user->active_status) {
            return redirect()->back()->with('error', 'Sorry, your account has been disabled.');
        }

        session()->put('userLevel', 'user_admin.');

        if ($user->isSuperAdmin()) {
            session()->put('userLevel', 'admin.');
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('user_dashboard'));
    }
}
