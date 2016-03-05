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
     * Report metrics.
     * 
     * @var array $metrics
     */
    private $metrics = array('count');
    
    /**
     * Day of report.
     *
     * @var string $day
     */
    private $day;
    
    /**
     * Month of report.
     *
     * @var string $month
     */
    private $month;
    
    /**
     * Year of report.
     *
     * @var string $year
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
            $totalNumberOfDailyTransactions = DB::table('transactions')->whereBetween('date', array(
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
            $totalNumberOfMonthlyTransactions = DB::table('transactions')->whereBetween('date', array(
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