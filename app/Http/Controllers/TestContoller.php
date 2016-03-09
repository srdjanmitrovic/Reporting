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
class TestController extends Controller
{
    /**
     * Date of transaction data.
     *
     * @var array
     */
    private $aggregator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AggregationDispatcher $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * Show application dashboard with acquired reporting data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->aggregator->dispatch();

    }
}
