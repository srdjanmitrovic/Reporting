<?php
namespace App\Aggregation;

use DB;
use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{

    private $aggregator;

    public function __construct(TransactionAggregator $aggregator)
    {   
        $this->aggregator = $aggregator;
    }

    public function dispatch()
    {
        $date = explode('-', date('Y-m-d'));
        $time = explode('-', date('H-i-s'));
        $last_aggregated_transaction = DB::table('transaction_aggregation')->select('last_transaction_id')->where('month', '=', $date[1])->take(1)->get();
        if ($last_aggregated_transaction[0]->last_transaction_id == 0) {
            $this->aggregator->getNewProcessedData(date('d'), date('m'));
        }
        else {
            $this->aggregator->getCurrentProcessedData($last_aggregated_transaction[0]->last_transaction_id);
        }
        $this->aggregator->updateAggregationTable(date('d'), date('m'));
        $this->logMessage('Aggregated transaction data up to date');

    }

    private function logMessage($message)
    {
        echo '[' . date('Y') . '-' . date('m') . '-' . date('d') . ' ' . date('H') . ':' .  date('i') . ':' . date('s') . '] - ' . $message . ". \n";
    }
}