<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Repair_invoice;
use App\Shop;
use App\Subscription_payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.login');
    }

    public function dashboard()
    {
        $shopCount = Shop::all()->count();
        $userCount = User::where('user_type', 'user')->get()->count();

        $year = \date('Y');
        $sales_data = Invoice::whereRaw('YEAR(created_at) =' . $year)
            ->selectRaw('DATEPART(MM, created_at) as month, sum(total_price) as amount')
            ->groupBy('created_at')
            ->orderBy('created_at')
            ->get();

        $sales_value = Invoice::sum('total_price');
        $completed_repair = Repair_invoice::where('status', 'completed')->count();

        $label = '[';
        $amount = '';
        foreach ($sales_data as $data) {
            $label .= '"' . $data->month . '", ';
            $amount .= $data->amount . ', ';
        }
        $label .= ']';

        return view('admin.dashboard', \compact('shopCount', 'userCount', 'label', 'amount', 'sales_value', 'completed_repair'));
    }

    /**
     * All Subscriptions payment from merchant
     */
    public function payments()
    {
        $pageTitle = 'Subscription payments list';
        $payments = Subscription_payment::with('shop')->paginate(50);

        return view('admin.subscriptions_payments', \compact('payments', 'pageTitle'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('login_page'));
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';

        return view(session('userLevel') . 'change_password', \compact('pageTitle'));
    }

    public function changePasswordPost(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        //$new_password_confirmation = $request->new_password_confirmation;

        if (Auth::check()) {
            $logged_user = Auth::user();

            if (Hash::check($old_password, $logged_user->password)) {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();

                return redirect()->back()->with('success', 'Password changed success');
            }

            return redirect()->back()->with('error', 'Old password wrong');
        }
    }
}
