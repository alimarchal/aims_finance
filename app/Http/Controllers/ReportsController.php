<?php

namespace App\Http\Controllers;

use App\Models\Chit;
use App\Models\Department;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function reportDaily(Request $request)
    {
        // Only For OPD
        $date = $request->date;

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }

        $data = null;
        foreach (Department::where('name', '!=', 'Emergency')->get() as $dpt) {
            $data[$dpt->name] = ['Non_Entitiled' => 0, 'Entitiled' => 0, 'Revenue' => 0];
        }

        $non_entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Non_Entitiled'), DB::raw('SUM(chits.amount) as Revenue'))
            ->whereDate('chits.issued_date', $date)
            ->where('chits.government_non_gov', 0)
            ->where('ipd_opd', 1)
            ->groupBy('chits.department_id')
            ->get();

        $entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Entitiled'), DB::raw('SUM(chits.amount) as Revenue'))
            ->whereDate('chits.issued_date', $date)
            ->where('chits.government_non_gov', 1)
            ->where('ipd_opd', 1)
            ->groupBy('chits.department_id')
            ->get();


        // Update the $data array with figures from $non_entitled and $entitled queries
        foreach ($non_entitled as $row) {
            $data[$row->name]['Non_Entitiled'] = $row->Non_Entitiled;
            $data[$row->name]['Revenue'] = $row->Revenue;
        }


        foreach ($entitled as $row) {
            $data[$row->name]['Entitiled'] = $row->Entitiled;
        }

        return view('reports.reports-daily', compact('data'));
    }

    public function reportDailyIPD(Request $request)
    {
        // Only For IPD
        $date = $request->date;

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }

        $data = null;
        foreach (Department::where('name', '=', 'Emergency')->get() as $dpt) {
            $data[$dpt->name] = ['Non-Entitiled' => 0, 'Entitiled' => 0, 'Revenue' => 0];
        }

        $non_entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Non_Entitiled'), DB::raw('SUM(chits.amount) as Revenue'))
            ->whereDate('chits.issued_date', $date)
            ->where('chits.government_non_gov', 0)
            ->where('ipd_opd', 0)
            ->groupBy('chits.department_id')
            ->get();

        $entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Entitiled'), DB::raw('SUM(chits.amount) as Revenue'))
            ->whereDate('chits.issued_date', $date)
            ->where('chits.government_non_gov', 1)
            ->where('ipd_opd', 0)
            ->groupBy('chits.department_id')
            ->get();


        // Update the $data array with figures from $non_entitled and $entitled queries
        foreach ($non_entitled as $row) {
            $data[$row->name]['Non-Entitiled'] = $row->Non_Entitiled;
            $data[$row->name]['Revenue'] = $row->Revenue;
        }


        foreach ($entitled as $row) {
            $data[$row->name]['Entitiled'] = $row->Entitiled;
        }


        return view('reports.reports-daily-ipd', compact('data'));
    }

    public function index()
    {
        return view('reports.index');
    }

    public function opd()
    {
        return view('reports.opd.index');
    }

    public function ipd()
    {
        return view('reports.ipd.index');
    }
}
