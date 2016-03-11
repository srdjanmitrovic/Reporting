<?php 

namespace App\Aggregation;

use DB;
use Illuminate\Database\Eloquent\Model;

class TransactionAggregator extends Model
{		

    private $month_sale_sum = 0;

    private $month_commission_sum = 0;

    private $day_sale_sum = 0;

    private $day_commission_sum = 0;

    private $last_transaction_id;
	
	public function getNewProcessedData($day, $month)
	{
        $transaction_table_transactions = DB::table('transactions')->select('date', 'sale_amount', 'commission')->where('date', '>', '2016-' . $month . '-' . $day . ' 00:00:00')->get();
        $this->processData($transaction_table_transactions);
	}

	public function getCurrentProcessedData($transaction_id)
	{	
        $transaction_table_transactions = DB::table('transactions')->select('id', 'sale_amount', 'commission')->where('id', '>', $transaction_id)->get();
        $this->processData($transaction_table_transactions);
	}

    private function processData($transactions)
    {
        $this->last_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get()[0]->id;
        foreach($transactions as $transaction) {
            $this->month_sale_sum += $transaction->sale_amount;
            $this->month_commission_sum += $transaction->commission;
            $this->day_sale_sum += $transaction->sale_amount;
            $this->day_commission_sum += $transaction->commission;
        }   
    }

    public function updateAggregationTable($day, $month){
        DB::update('UPDATE production.transaction_aggregation SET last_transaction_id=' . $this->last_transaction_id  . ', commission_average = commission_average + 0, commission_sum = commission_sum + 3, sale_average = sale_average + 0, sale_sum = sale_sum + 0 WHERE month = ' . $month . ' AND day = ' . $day . ';');
    }
}