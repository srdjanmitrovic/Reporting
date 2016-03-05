<?php

namespace App\Reporting;

use DB;
use Illuminate\Database\Eloquent\Model;

class TransactionsReport extends Model implements ReportInterface
{
    
    /**
     * Set timestamps to False (True by default). 
     * @var boolean
     */
    public $timestamps = False;

    /**
     * 
     */
    private $metrics = array('count');

    /**
     * @param  int $day
     * @return int
     */
    public function getDaily($day)
    {
        foreach($this->metrics as $metric){
            $totalNumberOfDailyTransactions = DB::table('transactions')->whereBetween('date', array(
                '2016-02-' . $day . ' 00:00:00',
                '2016-02-' . $day . ' 23:59:59'
            ))->$metric();
        }
        return $totalNumberOfDailyTransactions;
    }
    
    /**
     * @param  int $month
     * @return int
     */
    public function getMonthly($month)
    {
        foreach($this->metrics as $metric){
            $totalNumberOfMonthlyTransactions = DB::table('transactions')->whereBetween('date', array(
                '2016-' . $month . '-01 00:00:00',
                '2016-' . $month . '-30 23:59:59'
            ))->$metric();
        }
        return $totalNumberOfMonthlyTransactions;
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