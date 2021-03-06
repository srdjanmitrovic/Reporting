<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Aggregation\Dispatcher;
use App\Aggregation\TransactionAggregator;

class AggregateTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to aggregate transaction values.';

    /**
     * Used to dispatch transaction processing events.
     * 
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {  
        $this->dispatcher->dispatchTransactionAggregation(new TransactionAggregator);
    }
}
