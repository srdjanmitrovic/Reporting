<?php

namespace App\Reporting;

use DB;
use Illuminate\Database\Eloquent\Model;

class RevenueReport extends Model implements ReportInterface
{
    
    /**
     * Set timestamps to False (True by default). 
     * @var boolean
     */
    public $timestamps = False;

    /**
     * 
     */
    private $metrics = array('sum', 'avg');

    /**
     * @param  int $day
     * @return int
     */
    public function getDaily($day)
    {
        foreach($this->metrics as $metric){
            $dailyRevenue[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-02-' . $day . ' 00:00:00',
                '2016-02-' . $day . ' 23:59:59'
            ))->$metric('sale_amount');
        }
        return $dailyRevenue;
    }
    
    /**
     * @param  int $month
     * @return int
     */
    public function getMonthly($month)
    {
        foreach($this->metrics as $metric){
            $monthlyRevenue[$metric] = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $month . '-01 00:00:00',
                '2016-' . $month . '-30 23:59:59'
            ))->$metric('sale_amount');
        }
        return $monthlyRevenue;
    }
    
    /**
     * @param  int $year
     * @return int
     */
    public function getYearly($year)
    {
        return $year;
    }
}