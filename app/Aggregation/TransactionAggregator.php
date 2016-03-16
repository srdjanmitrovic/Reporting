<?php

namespace App\Aggregation;

use DB;
use App\Logger;

class TransactionAggregator
{

    /**
     * Daily sale average.
     *
     * @var int
     */
    private $day_sale_average = 0;

    /**
     * Daily commission average.
     *
     * @var int
     */
    private $day_commission_average = 0;

    /**
     * Daily sale total.
     *
     * @var int
     */
    private $day_sale_sum = 0;

    /**
     * Daily commission total.
     *
     * @var int
     */
    private $day_commission_sum = 0;

    /**
     * Total number of transactions.
     *
     * @var int
     */
    private $number_of_transactions = 0;

    /**
     * Last transaction id.
     *
     * @var int
     */
    private $last_transaction_id;

    /**
     * Source  table.
     *
     * @var string
     */
    private $source_table;

    /**
     * Aggregation table.
     *
     * @var string
     */
    private $aggregation_table;

    /**
     * Set table where the data will be aggregated from.
     *
     * @param  string $table
     * @param  string $year
     * @param  string $month  
     * @return void
     */
    public function setSourceTable($table, $year = '', $month = '')
    {
        $this->source_table = $table . $year . $month;
    }

    /**
     * Set the table where the data will be stored.
     *
     * @param  string $table
     * @param  string $year
     * @param  string $month  
     * @return void
     */
    public function setAggregationTable($table, $year = '', $month = '')
    {
        $this->aggregation_table = $table . $year . $month;
    }

    /**
     * Get new data (run at the beginning of the day).
     *
     * @param  int $day
     * @param  int $month
     * @return void
     */
	public function getNewProcessedData($day, $month)
	{
        DB::update('UPDATE ' . $this->aggregation_table . ' SET last_transaction_id = 0, commission_sum = 0, sale_sum = 0, transaction_count = 0 WHERE month = ' . $month . ' AND day = ' . $day . ';');
        $transaction_table_transactions = DB::table($this->source_table)->select('date', 'sale_amount', 'commission')
                                                                        ->where('date', '>', '2016-' . $month . '-' . $day . ' 00:00:00')
                                                                        ->get();
        $this->processSums($transaction_table_transactions);
	}

    /**
     * Acquire latest data to be processed.
     *
     * @param  int $transaction_id
     * @return void
     */
	public function getCurrentProcessedData($transaction_id)
	{
        $transaction_table_transactions = DB::table($this->source_table)->select('id', 'sale_amount', 'commission')
                                                                        ->where('id', '>', $transaction_id)
                                                                        ->get();
        $this->processSums($transaction_table_transactions);
	}

    /**
     * Process sums.
     *
     * @param  array $transactions
     * @return void
     */
    private function processSums($transactions)
    {
        $this->last_transaction_id = DB::table($this->source_table)->select('id')
                                                                   ->orderBy('id', 'desc')
                                                                   ->take(1)
                                                                   ->get()[0]
                                                                   ->id;

        foreach ($transactions as $transaction) {
            $this->day_sale_sum += $transaction->sale_amount;
            $this->day_commission_sum += $transaction->commission;
            $this->number_of_transactions += 1;
        }
    }

    /**
     * Processs average values based on total transactions and sums.
     *
     * @param  string $day
     * @param  string $month
     * @return void
     */
    private function processAverages($day = '' , $month = '')
    {
        $aggregation_table_results = DB::table($this->aggregation_table)->select('transaction_count', 'sale_sum', 'commission_sum')
                                                                        ->where('day', '=', $day)
                                                                        ->where('month', '=', $month)
                                                                        ->get();
                                                                        
        foreach ($aggregation_table_results as $result) {
            if ($result->transaction_count != 0) {
                $this->day_commission_average = $result->commission_sum/$result->transaction_count;
                $this->day_sale_average = $result->sale_sum/$result->transaction_count;
            }
        }
    }

    /**
     * Update Aggregation table with relevant data.
     * 'updateAggregationSums' should be called first as updateAggregationAverages
     * uses the information stored by calling updateAggregationSums.
     *
     * @param  int $day
     * @param  int $month
     * @return void
     */
    public function updateAggregationTable($day, $month)
    {
         $this->updateAggregationSums($day, $month);
         $this->processAverages($day, $month);
         $this->updateAggregationAverages($day, $month);
    }

    /**
     * Updates the aggregation table sum columns ('sale_sum' and 'commission_sum') and last_transaction_id and 
     * transaction_count.
     * 
     * @param  int $day
     * @param  int $month
     * @return void
     */
    private function updateAggregationSums($day, $month)
    {
        DB::update('UPDATE ' . $this->aggregation_table . ' SET last_transaction_id=' . $this->last_transaction_id  . ', commission_sum = commission_sum + ' . $this->day_commission_sum . ', sale_sum = sale_sum + ' . $this->day_sale_sum . ', transaction_count = transaction_count + ' . $this->number_of_transactions . ' WHERE month = ' . $month . ' AND day = ' . $day . ';');

    }

    /**
     * Updates the aggregation table average columns ('sale_average' and 'commission_average').
     * 
     * @param  int $day
     * @param  int $month
     * @return void
     */
    private function updateAggregationAverages($day, $month)
    {
        DB::update('UPDATE ' . $this->aggregation_table . ' SET commission_average = ' . $this->day_commission_average . ', sale_average =  ' . $this->day_sale_average . ' WHERE month = ' . $month . ' AND day = ' . $day . ';');

    }

    /**
     * Used to parse results to the correct format.
     *
     * @param  array $transactions
     * @return void
     */
    public function parseResults($transactions)
    {

    }
}