<?php

namespace App\Reporting;

use DB;

/**
 * Used to generate the transaction statistics based on the relative metrics
 */
class TransactionRepository implements RepositoryInterface
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
    private $columns = array('daily'=>array('transaction_count', 'commission_sum','sale_sum','sale_average','commission_average'), 'monthly'=>array('transaction_count', 'commission_sum','sale_sum'));

    /**
     * Day of report.
     *
     * @var string 
     */
    private $date;

    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get transaction data for given day.
     *
     * @return int
     */
    public function getDailyStatistics()
    {
        foreach($this->columns['daily'] as $column){
            $totalNumberOfDailyTransactions[$column] = DB::table($this->aggregated_transactions_table)->select($column)->where('day','=',$this->date['day'])->where('month','=', $this->date['month'])->get();
        }
        return $totalNumberOfDailyTransactions;
    }

    /**
     * Get transaction data for given month.
     *
     * @return int
     */
    public function getMonthlyStatistics()
    {
        foreach($this->columns['monthly'] as $column){
            $totalNumberOfMonthlyTransactions[$column] = DB::table($this->aggregated_transactions_table)->where('month', '=', $this->date['month'])->whereBetween('day', array(1,$this->date['day']))->sum($column);
        }
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