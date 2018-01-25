<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\traits\LoadEmployees;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use LoadEmployees;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
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
        session()->put('userLevel', 'user_admin.');
        $stations = $user->stations;
        if (! \count($stations)) {
            session()->put('station_id', 0);
        }

        if (\count($stations) == 1) {
            session()->put('station_id', $stations->first()->id);
        }

        return response()->json([
            'success' => 'true',
            'access_token' => 'test',
            'user' => $user,
            'current_station' => session('station_id', 0)
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        if ($request->ajax()) {
            return response()->json(['success' => 'true']);
        }

        return redirect('/');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
}
