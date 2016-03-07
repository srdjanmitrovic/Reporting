<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function(){
            $offset = 0;
            $chunk = 100000;
            $commissionSum = 0;
            $commissionAverage = 0;
            $saleSum = 0;
            $saleAverage = 0;
            $lastAggregatedTransactionId = DB::table('transaction_aggregation')->orderBy('last_transaction_id', 'desc')->take(1)->get();
            $sizeOfTransactionsTable = DB::table('transactions')->count();
            $sizeOfTransactionAggregationTable = DB::table('transaction_aggregation')->count();
            if(empty($lastAggregatedTransactionId)){
                DB::insert("INSERT INTO production.transaction_aggregation (id, last_transaction_id, commission_average, commission_sum, sale_average, sale_sum) VALUES(0,0,0,0,0,0) ;");
                $lastAggregatedTransactionId = DB::table('transaction_aggregation')->orderBy('last_transaction_id', 'desc')->take(1)->get();
                if($lastAggregatedTransactionId[0]->id == 0){
                    if($sizeOfTransactionsTable > 0){
                        while($sizeOfTransactionAggregationTable < (0.8*$sizeOfTransactionsTable)){    
                            $chunkFromTransactionsTable = DB::table('transactions')->select('id')->orderBy('id', 'desc')->skip($offset)->take($chunk)->get();
                            $chunkFromTransactionsTable = DB::table('transactions')->select('sale_amount')->orderBy('id', 'desc')->skip($offset)->take($chunk)->get();
                            $chunkFromTransactionsTable = DB::table('transactions')->select('commission')->orderBy('id', 'desc')->skip($offset)->take($chunk)->get();
                            foreach($chunkFromTransactionsTable as $transaction){
                                if($commissionAverage == 0 && $saleAverage == 0){
                                    $saleAverage = ($saleAverage + $transaction->sale_amount);
                                    $commissionAverage = ($commissionAverage + $transaction->commission);
                                }else{
                                    $saleAverage = ($saleAverage + $transaction->sale_amount)/2;
                                    $commissionAverage = ($commissionAverage + $transaction->commission)/2;
                                }    
                                $commissionSum += $transaction->commission;
                                $saleSum += $transaction->sale_amount;
                                $id = $transaction->id;
                            }
                            echo 'hello';
                            $sizeOfTransactionAggregationTable += $chunk;
                            $offset = $chunk;
                            $chunk += $chunk;
                            DB::table('transaction_aggregation')->where('id' , 0)->update(['id'=>0,'last_transaction_id'=> $id, 'commission_average' => $commissionAverage, 'commission_sum' => $commissionSum, 'sale_sum' => $saleSum, 'sale_average'=>$saleAverage]);
                        }
                    }
                }
            }else{
                $transactionAggregationData = DB::table('transaction_aggregation')->select()->get();
                $aggregated_commission_average = $transactionAggregationData[0]->commission_average;
                $aggregated_commission_sum = $transactionAggregationData[0]->commission_sum;
                $aggregated_sale_sum = $transactionAggregationData[0]->sale_sum;
                $aggregated_sale_average = $transactionAggregationData[0]->sale_average;
                $aggregated_transaction_id = $transactionAggregationData[0]->last_transaction_id;
                $transactionData = DB::table('transactions')->select('id', 'sale_amount', 'commission')->where('id', '>', $aggregated_transaction_id)->take(10)->get();
                foreach($transactionData as $transaction)
                {
                    if($commissionAverage == 0 && $saleAverage == 0){
                        $saleAverage = ($saleAverage + $transaction->sale_amount);
                        $commissionAverage = ($commissionAverage + $transaction->commission);
                    }else{
                        $saleAverage = ($saleAverage + $transaction->sale_amount)/2;
                        $commissionAverage = ($commissionAverage + $transaction->commission)/2;
                    }    
                    $commissionSum += $transaction->commission;
                    $saleSum += $transaction->sale_amount;
                    $id = $transaction->id;
                }
                $aggregated_commission_sum += $commissionSum;
                $aggregated_commission_average += $commissionAverage;
                $aggregated_sale_average += $saleAverage;
                $aggregated_sale_sum += $saleSum;
                DB::table('transaction_aggregation')->where('id' , 0)->update(['id'=>0,'last_transaction_id'=> $id, 'commission_average' => $commissionAverage, 'commission_sum' => $commissionSum, 'sale_sum' => $saleSum, 'sale_average'=>$saleAverage]);
                // DB::insert("INSERT INTO production.transaction_aggregation (SELECT * FROM production.transactions);");
            }
            //$nonAggregatedData = DB::table('transactions')->select('id','sale_amount','commission')->where('id','>',$lastAggregatedTransactionId[0]->id)->get();
            
            /*  END OF BLOCK */
            // var_dump($nonAggregatedData);
            // $var = $var[0]->id;
            // DB::table('test')->insert(['id' => ++$var, 'test' => 'testing']);
        })->everyMinute();
    }
}
