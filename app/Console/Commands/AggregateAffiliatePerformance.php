<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Aggregation\Dispatcher;
use App\Aggregation\AffiliateAggregator;

class AggregateAffiliatePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliates:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to aggregate affiliate performance.';

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
        $this->dispatcher->dispatchAffiliatePerformanceAggregation(new AffiliateAggregator);
    }
}
