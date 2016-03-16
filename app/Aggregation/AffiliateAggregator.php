<?php 

namespace App\Aggregation;

use DB;
use App\Logger;

class AffiliateAggregator implements AggregatorInterface
{		

    /**
     * Source table.
     * 
     * @var string
     */
    private $source_table;

    /**
     * Aggregation table.
     * 
     * @var string
     */
    private $aggregation_table;

    /**
     * Set table where the data will be aggregated from.
     * 
     * @param  string $table
     * @param  string $month
     * @param  string $year
     * @return void
     */
    public function setSourceTable($table, $year = '', $month = '')
    {
        $this->source_table = $table . $year . $month;
    }

    /**
     * Set the table where the data will be stored.
     * 
     * @param  string $table
     * @param  string $month
     * @param  string $year
     * @return void
     */
    public function setAggregationTable($table, $year = '', $month = '')
    {
        $this->aggregation_table = $table . $year . $month;
    }    

    /**
     * Get data from the source table and pull the
     * 
     * @return void
     */
    public function rankAffiliatesByRevenue()
    {
        $this->affiliates = DB::select("SELECT SUM(sale_amount) AS 'revenue', affiliate_id FROM " . $this->source_table . " GROUP BY (affiliate_id) ORDER BY sale_amount DESC LIMIT 1000;");
    }

    /**
     * Update relevenat aggregation table.
     * 
     * @return void
     */
    public function updateAggregationTable()
    {
        DB::table($this->aggregation_table)->truncate();
        foreach ($this->affiliates as $affiliate) {
            DB::table($this->aggregation_table)->insert(['affiliate_id'=>$affiliate->affiliate_id, 
                                                         'revenue'     =>$affiliate->revenue]);
        }
    }

    /**
     * Parse results to be able to update the relevant aggregation table.
     * 
     * @param  array $affiliates 
     * @return void
     */
    public function parseResults($affiliates)
    {

    }

}