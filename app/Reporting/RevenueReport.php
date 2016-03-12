<?php

namespace App\Reporting;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Used to generate the revenue statistics based on the relative metrics
 */
class RevenueReport extends Model implements ReportInterface
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
    private $metrics = array('sum', 'avg');

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
     * Generate complete revenue report.
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
     * Get revenue data for given year.
     *
     * @return int
     */
    public function getDaily()
    {
       $dailyRevenue['sum'] = DB::table($this->aggregated_transactions_table)->select('sale_sum')->where('month','=',$this->month)->where('day','=',$this->day)->get();
        return $dailyRevenue;
    }

    /**
     * Get revenue data for given month.
     *
     * @return int
     */
    public function getMonthly()
    {
        $monthlyRevenue['sum'] = DB::table($this->aggregated_transactions_table)->where('month', '=', $this->month)->whereBetween('day', array(
            1,
            $this->day
        ))->sum('sale_sum');
        return $monthlyRevenue;
    }

    /**
     * Get revenue data for given year.
     *
     * @return string
     */
    public function getYearly()
    {
        return '2016';
    }
}