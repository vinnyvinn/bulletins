<?php

namespace App\Http\Controllers;

use App\Invoice;
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

    public function dashboard()
    {
        $user = Auth::user();

        $shop = $user->shop;

        $year = date('Y');
        $sales_data = Invoice::where('shop_id', $shop->id)
            ->whereRaw('YEAR(created_at) ='.$year)
            ->selectRaw('MONTHNAME(created_at) as month, sum(total_price) as amount')
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        $label = '[';
        $amount = '';
        foreach($sales_data as $data) {
            $label .= '"'.$data->month.'", ';
            $amount .= $data->amount.', ';
        }
        $label .= ']';


        return view('user_admin.dashboard', compact('user', 'label', 'amount'));
    }
}
