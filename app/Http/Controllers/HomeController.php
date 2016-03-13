<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests;
use App\DateProcessor;
use Illuminate\Http\Request;
use App\Reporting\NetworkReport;

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
    public function __construct(Request $request, DateProcessor $dateProcessor)
    {
        $this->middleware('auth');
        $this->date     = $dateProcessor->process(array(
            'day' => $request->day,
            'month' => $request->month
        ), $request->useDate);
    }

    /**
     * Show application dashboard with acquired reporting data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NetworkReport $report)
    {
        $reportData = $report->getReport($this->date);
        return view('/home')->with('reportData', $reportData);
    }
}
