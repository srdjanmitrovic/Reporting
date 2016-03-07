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
        foreach($this->metrics as $metric){
            $dailyRevenue[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $this->month . '-' . $this->day . ' 00:00:00',
                '2016-' . $this->month . '-' . $this->day . ' 23:59:59'
            ))->$metric('sale_amount');
        }
        return $dailyRevenue;
    }

    /**
     * Get revenue data for given month.
     *
     * @return int
     */
    public function getMonthly()
    {
        foreach($this->metrics as $metric){
            $monthlyRevenue[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $this->month . '-01 00:00:00',
                '2016-' . $this->month . '-30 23:59:59'
            ))->$metric('sale_amount');
        }
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