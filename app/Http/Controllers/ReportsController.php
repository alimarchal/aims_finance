<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Chit;
use App\Models\Department;
use App\Models\FeeCategory;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientTest;
use Carbon\Carbon;
use DB;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
        // this is invoices and chits report by user wise
        // Only For IPD
        $date = $request->date;

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }

        $data = null;
        $user_id = null;
        $users = null;
        $roleName = 'Front Desk/Receptionist';


        if ($request->input('user_id')) {
            $user_id = $request->user_id;
            $roleName = 'Front Desk/Receptionist';
            $users = \App\Models\User::role($roleName)->where('id', $user_id)->get();
        } else {
            $users = \App\Models\User::role($roleName)->get();
        }


        foreach ($users as $user) {
            $data[$user->id] = ['Name' => $user->name, 'Invoices' => 0, 'Chits' => 0, 'Invoices Entitled' => 0, 'Invoices Non Entitled' => 0, 'Chit Entitled' => 0, 'Chit Non Entitled' => 0];
        }


        foreach ($users as $user) {
            $data[$user->id] = ['Name' => $user->name,
                'Invoices Entitled' => Invoice::whereDate('created_at', $date)->where('user_id', $user->id)->where('government_non_government', 1)->count(),
                'Invoices Non Entitled' => Invoice::whereDate('created_at', $date)->where('user_id', $user->id)->where('government_non_government', 0)->count(),
                'Invoices' => Invoice::whereDate('created_at', $date)->where('user_id', $user->id)->sum('total_amount'),
                'Chits' => Chit::whereDate('created_at', $date)->where('user_id', $user->id)->sum('amount'),
                'Chit Entitled' => Chit::whereDate('created_at', $date)->where('user_id', $user->id)->where('government_non_gov', 1)->count(),
                'Chit Non Entitled' => Chit::whereDate('created_at', $date)->where('user_id', $user->id)->where('government_non_gov', 0)->count(),
            ];
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

    public function reportMisc(Request $request)
    {
        return view('reports.category-wise.misc');
    }

    public function categoryWise(Request $request)
    {
        $date = $request->date;

        if ($request->input('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }

        $date_start_at = $date . ' 00:00:00';
        $date_end_at = $date . ' 23:59:59';

        $fee_categories = FeeCategory::with('feeTypes')->get();
        $categories = [];

        foreach ($fee_categories as $fee_cat) {
            foreach ($fee_cat->feeTypes as $fee_type) {
                // Append the fee type to the category array
                if ($fee_type->id == 107 || $fee_type->id == 108 || $fee_type->id == 19 || $fee_type->id == 1) {
                    $categories[$fee_cat->name][$fee_type->type] = [
                        'Non Entitled' => Chit::whereDate('issued_date', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => Chit::whereDate('issued_date', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => Chit::whereDate('issued_date', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('amount'),
                        'fee_category_id' => $fee_cat->id,
                        'fee_type' => $fee_type->id
                        ];
                } else {
                    $categories[$fee_cat->name][$fee_type->type] = [
                        'Non Entitled' => PatientTest::whereDate('created_at', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => PatientTest::whereDate('created_at', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => PatientTest::whereDate('created_at', $date)->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('total_amount'),
                        'fee_category_id' => $fee_cat->id,
                        'fee_type' => $fee_type->id
                    ];
                }
            }
        }

//        dd($categories);

        return view('reports.category-wise.index', compact('categories'));
    }

    public function admission(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';

        $admissions = QueryBuilder::for(Admission::class)->with('invoice','patient')
            ->allowedFilters([
                'patient.sex',
                'disease',
                'category',
                'nok_name',
                'relation_with_patient',
                'address',
                'cell_no',
                'cnic_no',
                'village',
                AllowedFilter::exact('invoice.government_non_government'),
                AllowedFilter::exact('unit_ward'),
                AllowedFilter::exact('tehsil'),
                AllowedFilter::exact('district'),
                AllowedFilter::exact('patient_id'),
                AllowedFilter::exact('invoice_id'),
            ],)
            ->whereBetween('created_at', [$date_start_at, $date_end_at])
            ->get();


        return view('reports.general-information.index', compact('admissions'));
    }
}
