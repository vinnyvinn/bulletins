<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function redirect;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/');
    }

    public function home()
    {
        return view('home');
    }

    public function user(Request $request)
    {
        return Response::json([
            'user' => $request->user()
        ]);
    }

    public function selectStation(Request $request)
    {
        session()->put('station_id', $request->get('station'));

        return redirect('/');
    }
}
