<?php

namespace App\Http\Controllers;

use App\Models\Chit;
use App\Models\Invoice;
use App\Models\PatientTest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {


        $issued_chits = 0;
        $issued_invoices_revenue = 0;
        $issued_invoices = 0;
        $today_revenue = 0;
        $non_entitled = 0;
        $entitled = 0;
        $user = \Auth::user();

        // OPD Front Desk
        if ($user->hasRole('Front Desk/Receptionist')) {
            $issued_chits = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->count();
            $today_revenue = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->sum('amount');
            $non_entitled = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->where('government_non_gov', 0)->count();
            $entitled = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->where('government_non_gov', 1)->count();
            $issued_invoices = Invoice::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->count();

            $issued_invoices_revenue = Invoice::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->sum('total_amount');



        } elseif ($user->hasRole(['Administrator'])) {

            $issued_chits = Chit::whereDate('issued_date', Carbon::today())->count();
            $today_revenue = Chit::whereDate('issued_date', Carbon::today())->sum('amount');
            $non_entitled = Chit::whereDate('issued_date', Carbon::today())->where('government_non_gov', 0)->count();
            $entitled = Chit::whereDate('issued_date', Carbon::today())->where('government_non_gov', 1)->count();
            $issued_invoices = Invoice::whereDate('created_at', Carbon::today())->count();

            $issued_invoices_revenue = Invoice::whereDate('created_at', Carbon::today())->sum('total_amount');


        }
        return view('dashboard', compact('issued_chits', 'today_revenue', 'non_entitled', 'entitled','issued_invoices','issued_invoices_revenue'));
    }
}
