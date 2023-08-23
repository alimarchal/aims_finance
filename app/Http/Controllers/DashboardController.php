<?php

namespace App\Http\Controllers;

use App\Models\Chit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $issued_chits = 0;
        $today_revenue = 0;
        $non_entitled = 0;
        $entitled = 0;
        $user = \Auth::user();

        // OPD Front Desk
        if ($user->hasRole('OPD Front Desk')) {
            $issued_chits = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->count();
            $today_revenue = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->sum('amount');
            $non_entitled = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->where('government_non_gov', 0)->count();
            $entitled = Chit::where('user_id', $user->id)->whereDate('issued_date', Carbon::today())->where('government_non_gov', 1)->count();
        } elseif ($user->hasRole(['Super-Admin', 'admin'])) {
            $issued_chits = Chit::whereDate('issued_date', Carbon::today())->count();
            $today_revenue = Chit::whereDate('issued_date', Carbon::today())->sum('amount');
            $non_entitled = Chit::whereDate('issued_date', Carbon::today())->where('government_non_gov', 0)->count();
            $entitled = Chit::whereDate('issued_date', Carbon::today())->where('government_non_gov', 1)->count();
        }

        return view('dashboard', compact('issued_chits', 'today_revenue', 'non_entitled', 'entitled'));
    }
}
