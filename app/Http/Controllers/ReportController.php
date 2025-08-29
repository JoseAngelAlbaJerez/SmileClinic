<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Budget;
use App\Models\CXC;
use App\Models\Expenses;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function patient(Patient $patient)
    {
        $pdf = Pdf::loadView('Reports.patient', compact('patient'))->setPaper('a4');
        return $pdf->stream('patient.pdf');
    }
    public function budget(Budget $budget)
    {
        $budget->load(['budgetdetail', 'doctor', 'patient', 'CXC']);

        return Pdf::loadView('Reports.budget', compact('budget'))->setPaper('a4', 'landscape')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->stream('budget.pdf');
    }
    public function fields(Request $request)
    {
        $table = $request->get('table');

        if (!$table || !Schema::hasTable($table)) {
            return response()->json(['error' => 'Invalid table'], 400);
        }

        $columns = Schema::getColumnListing($table);

        return response()->json($columns);
    }
    public function generate(Request $request, $table)
    {
        if (!Schema::hasTable($table)) {
            return back()->with('error', 'Invalid table.');
        }

        $fields   = $request->input('fields', []);
        $dateFrom = $request->input('dateFrom');
        $dateTo   = $request->input('dateTo');
        $orderBy  = $request->input('orderBy');
        $orderDir = $request->input('orderDirection', 'asc');
        $limit    = $request->input('limit');
        $search   = $request->input('search');
        $active   = $request->boolean('active', false);
        $groupBy  = $request->input('groupBy');

        $columns = !empty($fields) ? $fields : Schema::getColumnListing($table);

        $query = DB::table($table)->select($columns);

        if ($dateFrom && $dateTo && Schema::hasColumn($table, 'created_at')) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        if ($active && Schema::hasColumn($table, 'active')) {
            $query->where('active', true);
        }

        if ($search) {
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }


        if ($orderBy && Schema::hasColumn($table, $orderBy)) {
            $query->orderBy($orderBy, $orderDir);
        }

        if ($limit && is_numeric($limit)) {
            $query->limit($limit);
        }

        $data = $query->get();

        return Inertia::render('Reports/View', [
            'table'     => $table,
            'fields'    => $columns,
            'data'      => $data,
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
            'orderBy'   => $orderBy,
            'orderDir'  => $orderDir,
            'limit'     => $limit,
            'search'    => $search,
            'active'    => $active,
            'groupBy'   => $groupBy,
        ]);
    }

    public function bill(Bill $bill)
    {
        $bill->load(['billdetail', 'doctor', 'patient', 'CXC']);

        return Pdf::loadView('Reports.bill', compact('bill'))->setPaper('a4', 'landscape')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->stream('bill.pdf');
    }
    public function CXC(CXC $CXC)
    {
        $CXC->load(['Budget', 'CXCDetail', 'patient', 'Payment']);

        return Pdf::loadView('Reports.CXC', compact('CXC'))->setPaper('a4', 'landscape')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->stream('CXC.pdf');
    }
    public function budgets($Days)
    {

        $now = now();

        switch ($Days) {
            case '1':
            case '7':
            case '30':
                $date = $now->subDays((int)$Days);
                break;
            case 'month':
                $date = $now->copy()->subMonth()->startOfMonth();
                break;
            case 'year':
                $date = $now->copy()->subYear()->startOfYear();
                break;
            default:
                $date = $now->startOfDay();
                break;
        }

        $budgets = Budget::where('created_at', '>=', $date)
            ->where('active', true)
            ->get();

        $pdf = Pdf::loadView('Reports.budgets', compact('budgets', 'date', 'Days'))->setPaper('a4', 'portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream('budgets.pdf');
    }
    public function prescription(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'prescriptionsDetails.drugs']);
        $prescription->patient->age = \Carbon\Carbon::parse( $prescription->patient->date_of_birth)->age;
        return Inertia::render('Reports/Prescription', [
            'prescription' => $prescription,
        ]);
    }

    public function expenses($Days)
    {

        $now = now();

        switch ($Days) {
            case '1':
            case '7':
            case '30':
                $date = $now->subDays((int)$Days);
                break;
            case 'month':
                $date = $now->copy()->subMonth()->startOfMonth();
                break;
            case 'year':
                $date = $now->copy()->subYear()->startOfYear();
                break;
            default:
                $date = $now->startOfDay();
                break;
        }

        $expenses = Expenses::where('created_at', '>=', $date)
            ->where('active', true)
            ->get();

        $pdf = Pdf::loadView('Reports.expenses', compact('expenses', 'date', 'Days'))->setPaper('a4', 'portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream('expenses.pdf');
    }
}
