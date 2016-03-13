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
     * @var id
     */
    private $last_transaction_id;

    /**
     * Transaction tab;e
     * @var string
     */
    private $transactions_table;

    /**
     * Logger instance.
     * 
     * @var Logger.
     */
    private $logger;

    /**
     * Create a new TransactionAggregator instance.
     * 
     * @param Logger $logger 
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set transactions table.
     * 
     * @param  int $month
     * @param  int $year
     * @return void
     */
    public function setTable($month, $year)
    {
        $this->transactions_table = 'transactions'.$year.$month;
    }
	
    /**
     * Get new data (run at the beginning of the day).
     * @param  int $day   
     * @param  int $month
     * @return void
     */
	public function getNewProcessedData($day, $month)
	{
        $transaction_table_transactions = DB::table($this->transactions_table)->select('date', 'sale_amount', 'commission')->where('date', '>', '2016-' . $month . '-' . $day . ' 00:00:00')->get();
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
        $transaction_table_transactions = DB::table($this->transactions_table)->select('id', 'sale_amount', 'commission')->where('id', '>', $transaction_id)->get();
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
        $this->last_transaction_id = DB::table($this->transactions_table)->select('id')->orderBy('id', 'desc')->take(1)->get()[0]->id;
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
    private function processAverages($transaction_count){
        $this->day_sale_average = $this->day_sale_sum/$transaction_count;
        $this->day_commission_average = $this->day_commission_sum/$transaction_count;
    }

    /**
     * Update Aggregation table with relevant data.
     * 
     * @param  int $day  
     * @param  int $month
     * @return void
     */
    public function updateAggregationTable($day, $month){
        DB::update('UPDATE production.transaction_aggregation SET last_transaction_id=' . $this->last_transaction_id  . ', commission_sum = commission_sum + ' . $this->day_commission_sum . ', sale_sum = sale_sum + ' . $this->day_sale_sum . ', transaction_count = transaction_count + ' . $this->number_of_transactions . ' WHERE month = ' . $month . ' AND day = ' . $day . ';');

        $transaction_count = DB::table('transaction_aggregation')->select('transaction_count')->orderBy('id','desc')->take(1)->get();
        $this->processAverages($transaction_count[0]->transaction_count);

        DB::update('UPDATE production.transaction_aggregation SET commission_average = commission_average + ' . $this->day_commission_average . ', sale_average = sale_average + ' . $this->day_sale_average . ' WHERE month = ' . $month . ' AND day = ' . $day . ';');
    }
}