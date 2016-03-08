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
            $aggregation_transaction_id = DB::table('transaction_aggregation')->select('id')->get();
            if(empty($aggregation_transaction_id)){
                $transaction_sale_amount_sum = DB::table('transactions')->select('sale_amount')->sum('sale_amount');
                $transaction_sale_amount_average = DB::table('transactions')->select('sale_amount')->avg('sale_amount');
                $transaction_commission_sum = DB::table('transactions')->select('commission')->sum('commission');
                $transaction_commisssion_average = DB::table('transactions')->select('commission')->sum('commission');
                $transaction_transaction_id = DB::table('transactions')->select('id')->orderBy('id', 'desc')->take(1)->get();
                DB::table('transaction_aggregation')->insert(['id'=>0, 'last_transaction_id'=>$transaction_transaction_id[0]->id, 'commission_average'=>(int)$transaction_commisssion_average, 'commission_sum'=>(int)$transaction_commission_sum, 'sale_average'=>(int)$transaction_sale_amount_average, 'sale_sum'=>(int)$transaction_sale_amount_sum]);
            }
        })->everyMinute();
    }
}
