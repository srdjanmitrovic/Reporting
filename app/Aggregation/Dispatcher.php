<?php
namespace App\Aggregation;

use DB;
use App\Logger;

class Dispatcher 
{
    /**
     * Aggregates data using information provided by the Dispatcher.
     * 
     * @var TransactionAggregator
     */
    private $aggregator;

    /**
     * Used to log message based on aggregation result.
     * 
     * @var Logger
     */
    private $logger;

    /**
     * Create a new Dispatcher instance.
     * @param TransactionAggregator $aggregator 
     */
    public function __construct(TransactionAggregator $aggregator, Logger $logger)
    {   
        $this->aggregator = $aggregator;
        $this->logger = $logger;
    }

    /**
     * Dispatch the relative aggregation processes to the aggregator.
     * 
     * @return void 
     */
    public function dispatch()
    {
        $date = explode('-', date('Y-m-d'));
        $time = explode('-', date('H-i-s'));
        $last_aggregated_transaction = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month', '=', $date[1])->take(1)->get();
        $this->aggregator->setTable(date('m'), date('Y'));
        if ($last_aggregated_transaction[0]->last_transaction_id == 0) {
            $this->aggregator->getNewProcessedData(date('d'), date('m'));
        }
        else {
            $this->aggregator->getCurrentProcessedData($last_aggregated_transaction[0]->last_transaction_id);
        }
        $this->aggregator->updateAggregationTable(date('d'), date('m'));
        $this->logger->logMessage('Aggregated transaction data up to date');

    }
}