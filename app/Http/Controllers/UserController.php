<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'status' => 'success',
            'users' => User::where('id', '>', 1)->get()
        ]);
    }

    public function workIndex()
    {
        $pageTitle = 'All Staff List';
        return view('admin.agents', compact('pageTitle'));
    }


    public function logout()
    {
        Auth::logout();
        return redirect(route('sign_in'));
    }
}
