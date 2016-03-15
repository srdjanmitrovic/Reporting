<?php 

namespace App\Aggregation;

use DB;
use App\Logger;

class TransactionAggregator 
{		
    
    /**
     * Monthly sale total.
     * 
     * @var int
     */
    private $month_sale_sum = 0;

    /**
     * Monthly commission total.
     * 
     * @var int
     */
    private $month_commission_sum = 0;

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
     * @var id
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
     * @param  int $month
     * @param  int $year
     * @return void
     */
    public function setSourceTable($table, $month = '', $year = '')
    {
        $this->source_table = $table.$year.$month;
    }

    /**
     * Set the table where the data will be stored.
     * 
     * @param [type] $table [description]
     */
    public function setAggregationTable($table, $month = '', $year = '')
    {
        $this->aggregation_table = $table.$year.$month;
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
        $transaction_table_transactions = DB::table($this->source_table)->select('date', 'sale_amount', 'commission')->where('date', '>', '2016-' . $month . '-' . $day . ' 00:00:00')->get();
        $this->processSums($transaction_table_transactions);   
        $this->processAverages($day, $month);
	}

    /**
     * Acquire latest data to be processed.
     * 
     * @param  int $transaction_id
     * @return void
     */
	public function getCurrentProcessedData($transaction_id)
	{	
        $transaction_table_transactions = DB::table($this->source_table)->select('id', 'sale_amount', 'commission')->where('id', '>', $transaction_id)->get();
        $this->processSums($transaction_table_transactions);
        $this->processAverages();
	}

    /**
     * Process sums.
     * 
     * @param  array $transactions 
     * @return void
     */
    private function processSums($transactions)
    {
        $this->last_transaction_id = DB::table($this->source_table)->select('id')->orderBy('id', 'desc')->take(1)->get()[0]->id;
        foreach($transactions as $transaction) {
            $this->day_sale_sum += $transaction->sale_amount;
            $this->day_commission_sum += $transaction->commission;
            $this->number_of_transactions += 1;
        }   
    }

    /**
     * Processs average values based on total transactions and sums.
     * 
     * @param  int $transaction_count    Total transactions to now.
     * @return void
     */
    private function processAverages($day = '' ,$month = '')
    {        
        $aggregation_table_transaction_count = DB::table('transaction_aggregation')->select('transaction_count')->orderBy('id','desc')->take(1)->get()[0]->transaction_count;
        if ($aggregation_table_transaction_count != 0){
            $this->day_sale_average = $this->day_sale_sum/$aggregation_table_transaction_count;
            $this->day_commission_average = $this->day_commission_sum/$aggregation_table_transaction_count;
        }else{
            $transaction_table_transaction_count = DB::table($this->source_table)->select()->where('date', '>', '2016-' . $month . '-' . $day . ' 00:00:00')->count();
            if ($transaction_table_transaction_count == 0){
                $this->day_sale_average = 0;
                $this->day_commission_average = 0;
            }else{
                $this->day_sale_average = $this->day_sale_sum/$transaction_table_transaction_count;
                $this->day_commission_average = $this->day_sale_sum/$transaction_table_transaction_count;
            }   
        }
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

    /**
     * Update Aggregation table with relevant data.
     * 
     * @param  int $day  
     * @param  int $month
     * @return void
     */
    public function updateAggregationTable($day, $month){
        DB::update('UPDATE ' . $this->aggregation_table . ' SET last_transaction_id=' . $this->last_transaction_id  . ', commission_sum = commission_sum + ' . $this->day_commission_sum . ', sale_sum = sale_sum + ' . $this->day_sale_sum . ', transaction_count = transaction_count + ' . $this->number_of_transactions . ', commission_average = commission_average + ' . $this->day_commission_average . ', sale_average = sale_average + ' . $this->day_sale_average . ' WHERE month = ' . $month . ' AND day = ' . $day . ';');
    }
}