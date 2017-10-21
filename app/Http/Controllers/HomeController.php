<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
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

    public function selectContract(Request $request)
    {
        session()->put('contract_id', $request->get('contract'));

        return redirect('/ls');
    }

    public function localshunting()
    {
        return view('localshunting');
    }

    public function workshop()
    {
        return view('workshop');
    }
}
