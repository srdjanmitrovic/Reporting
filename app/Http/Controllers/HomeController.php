<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests;
use App\DateProcessor;
use App\Reporting\Reporter;
use Illuminate\Http\Request;
use App\Reporting\RevenueReport;
use App\Reporting\CommissionReport;
use App\Reporting\TransactionsReport;

/**
 * Calls relative models that generate reporting statistics
 * to be displayed by the respective views.
 */
class HomeController extends Controller
{
    /**
     * Date of transaction data.
     *
     * @var array
     */
    private $date;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Reporter $reporter, DateProcessor $dateProcessor)
    {
        $this->middleware('auth');
        $this->date     = $dateProcessor->process(array(
            'day' => $request->day,
            'month' => $request->month
        ), $request->useDate);
        $this->reporter = $reporter;
    }

    /**
     * Show application dashboard with acquired reporting data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactionData = $this->reporter->getReport(new TransactionsReport, $this->date);
        $commissionData  = $this->reporter->getReport(new CommissionReport, $this->date);
        $revenueData     = $this->reporter->getReport(new RevenueReport, $this->date);
        // var_dump($transactionData);
        return view('/home')->with('reportData', array('TransactionData'=>$transactionData, 'CommissionData' =>$commissionData, 'RevenueData'=>$revenueData));
    }
}
