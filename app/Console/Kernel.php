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
            $lastAggregatedTransactionId = DB::table('transaction_aggregation')->orderBy('id', 'desc')->take(1)->get();
            $sizeOfTransactionsTable = DB::table('transactions')->count();
            $sizeOfTransactionAggregationTable = DB::table('transaction_aggregation')->count();
            if(empty($lastAggregatedTransactionId)){
                if($sizeOfTransactionsTable > 0){
                    $offset = 0;
                    $chunk = 100000;
                    while($sizeOfTransactionAggregationTable < (0.8*$sizeOfTransactionsTable)){    
                        $chunkFromTransactionsTable = DB::table('transactions')->orderBy('id', 'desc')->skip($offset)->take($chunk)->get();
                        $sizeOfTransactionAggregationTable += $chunk;
                        foreach($chunkFromTransactionsTable as $transaction){
                            $transaction = json_decode(json_encode($transaction), true);
                            DB::table('transaction_aggregation')->insert($transaction);
                        } 
                        $offset = $chunk;
                        $chunk += $chunk;
                    }
                }else{
                    DB::insert("INSERT INTO production.transaction_aggregation (SELECT * FROM production.transactions);");
                }
            }
            //$nonAggregatedData = DB::table('transactions')->select('id','sale_amount','commission')->where('id','>',$lastAggregatedTransactionId[0]->id)->get();
            
            /*  END OF BLOCK */
            // var_dump($nonAggregatedData);
            // $var = $var[0]->id;
            // DB::table('test')->insert(['id' => ++$var, 'test' => 'testing']);
        })->everyMinute();
    }
}
