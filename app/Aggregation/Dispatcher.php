<?php
namespace App\Aggregation;

use DB;
use App\Logger;

class Dispatcher 
{

    /**
     * Used to log message based on aggregation result.
     * 
     * @var Logger
     */
    private $logger;

    /**
     * Create a new Dispatcher instance.
     * 
     * @param TransactionAggregator $aggregator 
     */
    public function __construct(Logger $logger)
    {   
        $this->logger = $logger;
    }

    /**
     * Dispatch the relative aggregation processes to the aggregator.
     * 
     * @param  TransactionAggregator $aggregator
     * @return void 
     */
    public function dispatchTransactionAggregation(TransactionAggregator $aggregator)
    {
        $last_aggregated_transaction = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month', '=', date('m'))->take(1)->get();
        $aggregator->setSourceTable('transactions', date('m'), date('Y'));
        $aggregator->setAggregationTable('transaction_aggregation');
        if ($last_aggregated_transaction[0]->last_transaction_id == 0) {
            $aggregator->getNewProcessedData(date('d'), date('m'));
        }
        else {
            $aggregator->getCurrentProcessedData($last_aggregated_transaction[0]->last_transaction_id);
        }
        $aggregator->updateAggregationTable(date('d'), date('m'));
        $this->logger->logMessage('Aggregated transaction data up to date');

    }

    /**
     * Dispatch the relative aggregation processes to the aggregator.
     * 
     * @param  AffiliateAggregator $aggregator
     * @return void
     */
    public function dispatchAffiliatePerformanceAggregation(AffiliateAggregator $aggregator)
    {
        $aggregator->setSourceTable('transactions', date('m'), date('Y'));
        $aggregator->setAggregationTable('affiliate_aggregation');
        $aggregator->rankeAffiliatesByRevenue(date('d'), date('m'));
        $aggregator->updateAggregationTable();
    }
}