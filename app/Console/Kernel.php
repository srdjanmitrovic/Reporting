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
        Commands\Inspire::class,
        Commands\Aggregate::class,
        Commands\ProcessTransactionHistory::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
            $date = explode('-', date('Y-m-d'));
            DB::table('transaction_aggregation')->insert([  'month'=> $date[1] ,
                                                            'day'=>$date[2], 
                                                            'last_transaction_id'=>0, 
                                                            'commission_average'=>0, 
                                                            'commission_sum'=>0,
                                                            'sale_average'=>0, 
                                                            'sale_sum'=>0]); 
            DB::table('transaction_aggregation')->insert([  'month'=> $date[1] ,
                                                            'day'=>0, 
                                                            'last_transaction_id'=>0, 
                                                            'commission_average'=>0, 
                                                            'commission_sum'=>0,
                                                            'sale_average'=>0, 
                                                            'sale_sum'=>0]); 
        })->everyMinute();
        
        $schedule->command('aggregator:aggregate')->appendOutputTo('transaction_aggregation.log');
        }
}
