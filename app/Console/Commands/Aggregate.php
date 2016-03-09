<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AggregationDispatcher;

class Aggregate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $aggregation;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AggregationDispatcher $aggregation)
    {
        $this->aggregation = $aggregation;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {  
        $this->aggregation->dispatch();
        $this->info('done');
    }
}
