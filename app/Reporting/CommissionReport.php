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
     * Specify respective sql functions to be used a statistics metrics.
     *
     * @var array $metrics
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
        foreach($this->metrics as $metric){
            $dailyCommission[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $this->month . '-' . $this->day . ' 00:00:00',
                '2016-' . $this->month . '-' . $this->day . ' 23:59:59'
            ))->$metric('commission');
        }
        return $dailyCommission;
    }

    /**
     * Get commission data for given month.
     *
     * @return int
     */
    public function getMonthly()
    {
        foreach($this->metrics as $metric){
            $monthlyCommission[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $this->month . '-01 00:00:00',
                '2016-' . $this->month . '-30 23:59:59'
            ))->$metric('commission');
        }
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