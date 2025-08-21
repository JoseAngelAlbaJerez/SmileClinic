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

        return Pdf::loadView('Reports.budget', compact('budget'))->setPaper('a4','landscape')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->stream('budget.pdf');
    }
      public function bill(Bill $bill)
    {
        $bill->load(['billdetail', 'doctor', 'patient', 'CXC']);

        return Pdf::loadView('Reports.bill', compact('bill'))->setPaper('a4','landscape')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->stream('bill.pdf');
    }
      public function CXC(CXC $CXC)
    {
        $CXC->load(['Budget', 'CXCDetail', 'patient', 'Payment']);

        return Pdf::loadView('Reports.CXC', compact('CXC'))->setPaper('a4','landscape')->setOptions([
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

        $pdf = Pdf::loadView('Reports.budgets', compact('budgets', 'date', 'Days'))->setPaper('a4','portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream('budgets.pdf');
    }
    public function prescription(Prescription $prescription)
    {

        $pdf = Pdf::loadView('Reports.prescription', compact('prescription'))->setPaper('a4')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);
        return  $pdf->stream('prescription.pdf');
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

        $pdf = Pdf::loadView('Reports.expenses', compact('expenses', 'date', 'Days'))->setPaper('a4','portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf->stream('expenses.pdf');
    }
}
