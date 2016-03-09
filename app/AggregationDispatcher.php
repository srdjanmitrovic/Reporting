<?php 

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class AggregationDispatcher extends Model
{
	public function dispatch()
	{
		$date = explode('-', date('Y-m-d'));
            $month_sale_sum = 0;
            $month_commission_sum = 0;
            $day_sale_sum = 0;
            $day_commission_sum = 0;
            //Get transaction and aggregation table count, (to calculate average for transactions) and (to see if aggregation table is empty).
            $transaction_table_count = DB::table('transactions')->select('id')->count();
            $aggregation_table_count = DB::table('transaction_aggregation')->select('id')->count();
            echo "1. Going to run historical aggregation.";
            if($aggregation_table_count == 0){
                echo "2. Aggregation table is empty.";
                $sale_Sum = 0;
                $commission_sum = 0;
                //As we've confirmed the table is empty, add in the day row and the month row.
                DB::table('transaction_aggregation')->insert([  'month'=> $date[1] ,'day'=>$date[2], 'last_transaction_id'=>0, 'commission_average'=>0, 'commission_sum'=>0,'sale_average'=>0, 'sale_sum'=>0]);
                DB::table('transaction_aggregation')->insert([  'month'=> $date[1] ,'day'=>0, 'last_transaction_id'=>0, 'commission_average'=>0, 'commission_sum'=>0,'sale_average'=>0, 'sale_sum'=>0]);
                $dataChunkSize = round($transaction_table_count/$date[2]+7,0);
                echo "3. Aggregating transactions now!";
                //Run query based on chunks (please debug numbers in $month_sale_sum and $month_commission_sum).
                DB::table('transactions')->select('sale_amount','commission')->chunk($dataChunkSize, function($transactions) use (&$month_sale_sum,&$month_commission_sum){
                    foreach($transactions as $transaction)
                    {
                        $month_sale_sum += $transaction->sale_amount;
                        $month_commission_sum += $transaction->commission;
                    }
                });
                echo "4. Completed monthly aggregation.";
                //Run query based on chunks (might fail due to 'where->()' statement after select).
                DB::table('transactions')->select('sale_amount','commission')->where('date', '>','2016-'.$date[1].'-'.$date[2].' 00:00:00')->chunk($dataChunkSize, function($transactions) use (&$day_sale_sum,&$day_commission_sum){
                    foreach($transactions as $transaction)
                    {
                        $day_sale_sum += $transaction->sale_amount;
                        $day_commission_sum += $transaction->commission;
                    }
                });
                $transaction_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
                echo "5. Transactions Aggregated, now inserting into transaction_aggregation.";
                //Update the previously inserted rows with the relevant values.
                
                DB::table('transaction_aggregation')->where('month',$date[1])->where('day',$date[2])->update(['last_transaction_id'=>$transaction_transaction_id[0]->id, 'commission_average'=>   0  , 'commission_sum'=>$month_commission_sum, 'sale_average'=>   0  , 'sale_sum'=>$month_sale_sum]);   

                DB::table('transaction_aggregation')->where('month',$date[1])->where('day'   ,   0 )->update(['last_transaction_id'=>$transaction_transaction_id[0]->id, 'commission_average'=>   0  , 'commission_sum'=>$day_commission_sum, 'sale_average'=>   0    , 'sale_sum'=>$day_sale_sum]);  
                echo "6. Completed insert, please check transaction_aggregation.";
            }else{
                echo "2. Running recent transaction aggregation.";
                $aggregation_last_transaction_id = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month','=',$date[1])->take(1)->get();
                if($aggregation_last_transaction_id[0]->last_transaction_id == 0){
                    $transactions_last_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
                    echo "3. Testing on a brand new day.";
                    $transaction_table_transactions   = DB::table('transactions')->select('date','sale_amount', 'commission')->where('date','>', "2016-$date[1]-$date[2] 00:00:00")->get();
                    foreach($transaction_table_transactions as $transaction){
                        $month_sale_sum += $transaction->sale_amount;
                        $month_commission_sum += $transaction->commission;
                        $day_sale_sum += $transaction->sale_amount;
                        $day_commission_sum += $transaction->commission;
                    }
                }else{
                    $transactions_last_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
                    echo "3. Testing on part of a day.";
                    $transaction_table_transactions   = DB::table('transactions')->select('id','sale_amount', 'commission')->where('id','>', $aggregation_last_transaction_id[0]->last_transaction_id)->get();
                    foreach($transaction_table_transactions as $transaction){
                        $month_sale_sum += $transaction->sale_amount;
                        $month_commission_sum += $transaction->commission;
                        $day_sale_sum += $transaction->sale_amount;
                        $day_commission_sum += $transaction->commission;
                    }
                }
                echo "4. Testing two";
                DB::insert("UPDATE production.transaction_aggregation SET last_transaction_id=".$transactions_last_transaction_id[0]->id.", commission_average= 0,   commission_sum=$month_sale_sum,    sale_average= 0, sale_sum=$month_sale_sum WHERE month= $date[1] AND (day = 0 OR day = $date[2]);");
                echo "5. Complete recent transaction aggregation, please verify.";
            }
	}
}