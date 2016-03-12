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
        Commands\AggregateTransactions::class,
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
            DB::table('transaction_aggregation')->insert([  'month'=> date('m') ,
                                                            'day'=>date('d'), 
                                                            'last_transaction_id'=>0, 
                                                            'commission_average'=>0, 
                                                            'commission_sum'=>0,
                                                            'sale_average'=>0, 
                                                            'sale_sum'=>0]); 
        })->everyMinute();
        
        $schedule->command('transactions:aggregate')->appendOutputTo('transaction_aggregation.log');
    }
}
