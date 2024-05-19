<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Chit;
use App\Models\Department;
use App\Models\FeeCategory;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientTest;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ReportsController extends Controller
{
    public function reportDaily(Request $request)
    {
        $user = \Auth::user();
        $issued_invoices = null;

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';

        $data = null;
        foreach (Department::where('name', '!=', 'Emergency')->get() as $dpt) {
            $data[$dpt->name] = ['Non_Entitiled' => 0, 'Entitiled' => 0, 'Revenue' => 0, 'Revenue_HIF' => 0, 'department_id' => $dpt->id];
        }


        $non_entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Non_Entitiled'), DB::raw('SUM(chits.amount) as Revenue, SUM(chits.amount_hif) as Revenue_HIF'))
            ->whereBetween('chits.issued_date', [$date_start_at, $date_end_at])
            ->where('chits.government_non_gov', 0)
            ->whereIn('ipd_opd', [1, 0])
            ->groupBy('chits.department_id')
            ->get();

        $entitled = DB::table('chits')
            ->join('departments', 'chits.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(chits.government_non_gov) AS Entitiled'), DB::raw('SUM(chits.amount) as Revenue, SUM(chits.amount_hif) as Revenue_HIF'))
            ->whereBetween('chits.issued_date', [$date_start_at, $date_end_at])
            ->where('chits.government_non_gov', 1)
            ->whereIn('ipd_opd', [1, 0])
            ->groupBy('chits.department_id')
            ->get();


        // Update the $data array with figures from $non_entitled and $entitled queries
        foreach ($non_entitled as $row) {
            $data[$row->name]['Non_Entitiled'] = $row->Non_Entitiled;
            $data[$row->name]['Revenue'] = $row->Revenue;
            $data[$row->name]['Revenue_HIF'] = $row->Revenue_HIF;
        }


        foreach ($entitled as $row) {
            $data[$row->name]['Entitiled'] = $row->Entitiled;
        }


        if ($user->hasRole('Auditor')) {
            return view('reports.auditor.reports-daily', compact('data'));
        } else {
            return view('reports.reports-daily', compact('data'));
        }


    }

    public function reportDailyIPD(Request $request)
    {

        $user = \Auth::user();
        // this is invoices and chits report by user wise
        // Only For IPD
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';


        $data = null;
        $user_id = null;
        $users = null;
        $roleName = 'Front Desk/Receptionist';


        if ($request->input('user_id')) {
            $user_id = $request->user_id;
            $roleName = 'Front Desk/Receptionist';
            $users = \App\Models\User::role($roleName)->where('id', $user_id)->get();
        } else {
            $users = \App\Models\User::where('id', '!=', 2)->get();
        }


        foreach ($users as $user) {
            $data[$user->id] = ['Name' => $user->name, 'Invoices' => 0, 'Invoices HIF' => 0, 'Chits' => 0, 'Chits HIF' => 0, 'Invoices Entitled' => 0, 'Invoices Non Entitled' => 0, 'Chit Entitled' => 0, 'Chit Non Entitled' => 0];
        }


        foreach ($users as $user) {
            $data[$user->id] = [
                'Name' => $user->name,
                'Invoices Entitled' => Invoice::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->where('government_non_government', 1)->count(),
                'Invoices Non Entitled' => Invoice::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->where('government_non_government', 0)->count(),
                'Invoices' => Invoice::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->sum('total_amount'),
                'Invoices HIF' => Invoice::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->sum('hif_amount'),
                'Chits' => Chit::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->sum('amount'),
                'Chits HIF' => Chit::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->sum('amount_hif'),
                'Chit Entitled' => Chit::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->where('government_non_gov', 1)->count(),
                'Chit Non Entitled' => Chit::whereBetween('created_at', [$date_start_at, $date_end_at])->where('user_id', $user->id)->where('government_non_gov', 0)->count(),
            ];
        }





        if (\Auth::user()->hasRole('Auditor')) {
            return view('reports.auditor.reports-daily-ipd', compact('data'));
        } elseif(\Auth::user()->hasRole(['Administrator','Front Desk/Receptionist'])) {

            return view('reports.reports-daily-ipd', compact('data'));
        }


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
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';

        $fee_categories = QueryBuilder::for(FeeCategory::class)->with('feeTypes')
            ->allowedFilters('name')
            ->allowedIncludes('feeTypes')
            ->get();

        $categories = [];

        foreach ($fee_categories as $fee_cat) {
            $fee_types_relation = null;
            if ($request->input('status')) {
                // The filter exists, retrieve its value
                $status = $request->input('status');

                $fee_types_relation = $fee_cat->feeTypes->where('status', $status);
            } else {
                // The filter does not exist, handle the case accordingly
                $fee_types_relation = $fee_cat->feeTypes;
            }


            foreach ($fee_types_relation as $fee_type) {
                // Append the fee type to the category array
                if ($fee_type->id == 107 || $fee_type->id == 108 || $fee_type->id == 19 || $fee_type->id == 1) {
                    $categories[$fee_cat->id][$fee_type->id] = [
                        'Non Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('amount'),
                        'HIF' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('amount_hif'),
                        'fee_category_id' => $fee_cat->id,
                        'fee_type_id' => $fee_type->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($fee_type->id)->type)->first(),
                    ];
                } else {
                    $categories[$fee_cat->id][$fee_type->id] = [
                        'Non Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('total_amount'),
                        'HIF' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $fee_type->id)->where('government_non_gov', 0)->sum('hif_amount'),
                        'fee_category_id' => $fee_cat->id,
                        'fee_type_id' => $fee_type->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($fee_type->id)->type)->first(),
                    ];
                }
            }
        }


        return view('reports.category-wise.index', compact('categories'));
    }

    public function department_wise(Request $request)

    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $user = \Auth::user();

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';

        $fee_types = null;
        $status = ['Normal', 'Return Fee'];
        $fee_category_ids = $request->input('filter.fee_category_id');
        $get_status_values = $request->input('status');

        if ($get_status_values !== null) {
            $status = explode(',', $get_status_values);
        }

        if ($fee_category_ids !== null) {
            // Split the string into an array of individual IDs
            $fee_category_ids = explode(',', $fee_category_ids);

            $fee_types = QueryBuilder::for(FeeType::class)
                ->orderBy('fee_category_id')
                ->orderByRaw('CASE WHEN status = "Normal" THEN 1 ELSE 2 END')
                ->whereIn('fee_category_id', $fee_category_ids)
                ->whereIn('status', $status)
                ->get();
        } else {
            $fee_types = QueryBuilder::for(FeeType::class)
                ->orderBy('fee_category_id')
                ->orderByRaw('CASE WHEN status = "Normal" THEN 1 ELSE 2 END')
                ->whereIn('status', $status)
                ->get();
        }


        $categories = [];


        if ( $request->input('status') == "Normal")
        {
            foreach ($fee_types as $ft) {
                if ($ft->id == 107 || $ft->id == 108 || $ft->id == 19 || $ft->id == 1) {
                    $categories[$ft->fee_category_id][$ft->id] = [
                        'Non Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->sum('amount'),
                        'HIF' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->sum('amount_hif'),
                        'fee_category_id' => $ft->fee_category_id,
                        'fee_type_id' => $ft->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($ft->id)->type)->first(),
                        'Status' => $ft->status,
                    ];
                } else {

                    $return_fee_id = 0;
                    $return_fee =  FeeType::where('type', 'Return ' . FeeType::find($ft->id)->type)->first();
                    if (!empty($return_fee)) {
                        $return_fee_id = $return_fee->id;
                    }


                    $categories[$ft->fee_category_id][$ft->id] = [
                        'Non Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->whereIn('fee_type_id', [$ft->id, $return_fee_id])->where('government_non_gov', 0)->sum('total_amount'),
                        'HIF' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->whereIn('fee_type_id', [$ft->id, $return_fee_id])->where('government_non_gov', 0)
//                            ->whereHas('fee_type', function($query) {
//                                $query->whereIn('status', ['Return Fee','Normal']);
//                            })
                        ->sum('hif_amount'),
                        'fee_category_id' => $ft->fee_category_id,
                        'fee_type_id' => $ft->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($ft->id)->type)->first(),
                        'Status' => $ft->status,
                    ];
                }
            }
        }

        else {
            foreach ($fee_types as $ft) {
                if ($ft->id == 107 || $ft->id == 108 || $ft->id == 19 || $ft->id == 1) {
                    $categories[$ft->fee_category_id][$ft->id] = [
                        'Non Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->sum('amount'),
                        'HIF' => Chit::whereBetween('issued_date', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->sum('amount_hif'),
                        'fee_category_id' => $ft->fee_category_id,
                        'fee_type_id' => $ft->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($ft->id)->type)->first(),
                        'Status' => $ft->status,
                    ];
                } else {
                    $categories[$ft->fee_category_id][$ft->id] = [
                        'Non Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->count(),
                        'Entitled' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 1)->count(),
                        'Revenue' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)->sum('total_amount'),
                        'HIF' => PatientTest::whereBetween('created_at', [$date_start_at, $date_end_at])->where('fee_type_id', $ft->id)->where('government_non_gov', 0)
                            ->whereHas('fee_type', function($query) {
                                $query->whereIn('status', ['Return Fee','Normal']);
                            })
                            ->sum('hif_amount'),
                        'fee_category_id' => $ft->fee_category_id,
                        'fee_type_id' => $ft->id,
                        'Returned Start Date' => $date_start_at,
                        'Returned End Date' => $date_end_at,
                        'Returned' => FeeType::where('type', 'Return ' . FeeType::find($ft->id)->type)->first(),
                        'Status' => $ft->status,
                    ];
                }
            }
        }





        if ($user->hasRole('Auditor')) {
            return view('reports.auditor.department-wise', compact('categories', 'fee_types'));
        } else {
            return view('reports.category-wise.department-wise', compact('categories', 'fee_types'));
        }



    }

    public function admission(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';

        $admissions = QueryBuilder::for(Admission::class)->with('invoice', 'patient')
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
