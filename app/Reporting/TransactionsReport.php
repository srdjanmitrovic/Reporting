<?php

namespace App\Reporting;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Used to generate the transaction statistics based on the relative metrics
 */
class TransactionsReport extends Model implements ReportInterface
{

    /**
     * Set timestamps to False (True by default).
     * @var boolean
     */
    public $timestamps = False;

    /**
     * SQL table containing aggregated transaction results.
     * 
     * @var string
     */
    private $aggregated_transactions_table = 'transaction_aggregation';

    /**
     * Specify respective sql functions to be used as statistics metrics.
     *
     * @var array 
     */
    private $metrics = array('count');

    /**
     * Day of report.
     *
     * @var string 
     */
    private $day;

    /**
     * Month of report.
     *
     * @var string 
     */
    private $month;

    /**
     * Year of report.
     *
     * @var string
     */
    private $year;

    /**
     * Generate complete transaction report.
     *
     * @param  array
     * @return array
     */
    public function generateReport($date)
    {
        $this->day   = $date['day'];
        $this->month = $date['month'];
        return array(
            'day' => $this->getDailyTransactionStatistics(),
            'month' => $this->getMonthlyTransactionStatistics(),
            'year' => $this->getYearlyTransactionStatistics()
        );
    }

    /**
     * Get transaction data for given day.
     *
     * @return int
     */
    public function getDailyTransactionStatistics()
    {
        $totalNumberOfDailyTransactions['count'] = DB::table($this->aggregated_transactions_table)->select('transaction_count')->where('month','=',$this->month)->where('day','=',$this->day)->get();
        return $totalNumberOfDailyTransactions;
    }

    /**
     * Get transaction data for given month.
     *
     * @return int
     */
    public function getMonthlyTransactionStatistics()
    {
        $totalNumberOfMonthlyTransactions['count'] = DB::table($this->aggregated_transactions_table)->where('month', '=', $this->month)->whereBetween('day', array(
            1,
            $this->day
        ))->sum('transaction_count');
        return $totalNumberOfMonthlyTransactions;
    }

    /**
     * Get transaction data for given year.
     *
     * @return string
     */
    public function getYearlyTransactionStatistics()
    {
        return '2016';
    }
}