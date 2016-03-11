<?php
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    public function dispatch()
    {
        $date = explode('-', date('Y-m-d'));
        $time = explode('-', date('H-i-s'));
        $month_sale_sum = 0;
        $month_commission_sum = 0;
        $day_sale_sum = 0;
        $day_commission_sum = 0;
        $aggregation_last_transaction_id = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month', '=', $date[1])->take(1)->get();
        if ($aggregation_last_transaction_id[0]->last_transaction_id == 0) {
            $transactions_last_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
            $transaction_table_transactions = DB::table('transactions')->select('date', 'sale_amount', 'commission')->where('date', '>', "2016-$date[1]-$date[2] 00:00:00")->get();
            foreach($transaction_table_transactions as $transaction) {
                $month_sale_sum+= $transaction->sale_amount;
                $month_commission_sum+= $transaction->commission;
                $day_sale_sum+= $transaction->sale_amount;
                $day_commission_sum+= $transaction->commission;
            }
        }
        else {
            $transactions_last_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
            $transaction_table_transactions = DB::table('transactions')->select('id', 'sale_amount', 'commission')->where('id', '>', $aggregation_last_transaction_id[0]->last_transaction_id)->get();
            foreach($transaction_table_transactions as $transaction) {
                $month_sale_sum+= $transaction->sale_amount;
                $month_commission_sum+= $transaction->commission;
                $day_sale_sum+= $transaction->sale_amount;
                $day_commission_sum+= $transaction->commission;
            }
        }
        DB::update("UPDATE production.transaction_aggregation SET last_transaction_id=" . $transactions_last_transaction_id[0]->id . ", commission_average = commission_average + 0, commission_sum = commission_sum + 3, sale_average = sale_average + 0, sale_sum = sale_sum + 0 WHERE month= $date[1] AND (day = $date[2] OR day = 0);");
        echo "[" . $date[0] . "-" . $date[1] . "-" . $date[2] . " " . $time[0] . ":" .  $time[1] . ":" . $time[2] . "] -\tUpdated latest transactions for the current timestamp. \n";

    }
}