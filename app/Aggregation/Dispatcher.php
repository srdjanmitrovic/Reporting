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
     * Used to store the current month and day.
     * 
     * @var array 
     */
    private $date;

    /**
     * Create a new Dispatcher instance.
     * 
     * @param TransactionAggregator $aggregator 
     */
    public function __construct(Logger $logger)
    {   
        $this->date = explode('-', date('Y-m-d'));
        $this->date = array('2016', '03', '01');
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
        $last_aggregated_transaction = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month', '=', $this->date[1])->orderBy('id','desc')->take(1)->get();
        $aggregator->setSourceTable('transactions', $this->date[0], $this->date[1]);
        $aggregator->setAggregationTable('transaction_aggregation');
        if ($last_aggregated_transaction[0]->last_transaction_id == 0) {
            $aggregator->getNewProcessedData($this->date[2], $this->date[1]);
        }
        else {
            $aggregator->getCurrentProcessedData($last_aggregated_transaction[0]->last_transaction_id);
        }
        $aggregator->updateAggregationTable($this->date[2], $this->date[1]);
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
        $aggregator->setSourceTable('transactions', $this->date[1], $this->date[0]);
        $aggregator->setAggregationTable('affiliate_aggregation');
        $aggregator->rankAffiliatesByRevenue();
        $aggregator->updateAggregationTable();
    }
}