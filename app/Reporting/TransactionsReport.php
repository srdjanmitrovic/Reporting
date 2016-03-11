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
            'day' => $this->getDaily(),
            'month' => $this->getMonthly(),
            'year' => $this->getYearly()
        );
    }

    /**
     * Get transaction data for given day.
     *
     * @return int
     */
    public function getDaily()
    {
        foreach ($this->metrics as $metric) {
            $totalNumberOfDailyTransactions[$metric] = DB::table($aggregated_transactions_table)->whereBetween('date', array(
                '2016-' . $this->month . '-' . $this->day . ' 00:00:00',
                '2016-' . $this->month . '-' . $this->day . ' 23:59:59'
            ))->$metric();
        }
        return $totalNumberOfDailyTransactions;
    }

    /**
     * Get transaction data for given month.
     *
     * @return int
     */
    public function getMonthly()
    {
        foreach ($this->metrics as $metric) {
            $totalNumberOfMonthlyTransactions[$metric] = DB::table($aggregated_transactions_table)->whereBetween('date', array(
                '2016-' . $this->month . '-01 00:00:00',
                '2016-' . $this->month . '-30 23:59:59'
            ))->$metric();
        }
        return $totalNumberOfMonthlyTransactions;
    }

    /**
     * Get transaction data for given year.
     *
     * @return string
     */
    public function getYearly()
    {
        return '2016';
    }
}