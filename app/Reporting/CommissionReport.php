<?php

namespace App\Reporting;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
* Used to generate the commission statistics based on the relative metrics.
*/
class CommissionReport extends Model implements ReportInterface
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
    private $metrics = array('sum');

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
     * Generate complete commission report.
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
     * Get commission data for given day.
     *
     * @return int
     */
    public function getDaily()
    {
        $dailyCommission['sum'] = DB::table($this->aggregated_transactions_table)->select('commission_sum')->where('month','=',$this->month)->where('day','=',$this->day)->get();
        return $dailyCommission;
    }

    /**
     * Get commission data for given month.
     *
     * @return int
     */
    public function getMonthly()
    {
        $monthlyCommission['sum'] = DB::table($this->aggregated_transactions_table)->where('month', '=', $this->month)->whereBetween('day', array(
            1,
            $this->day
        ))->sum('commission_sum');
        return $monthlyCommission;
    }

    /**
     * Get commission data for given year.
     *
     * @return string
     */
    public function getYearly()
    {
        return '2016';
    }
}