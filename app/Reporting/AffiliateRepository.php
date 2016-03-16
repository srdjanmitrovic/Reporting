<?php

namespace App\Reporting;

use DB;

class AffiliateRepository implements RepositoryInterface
{

	
	/**
	 * Aggregation table.
	 * 
	 * @var string
	 */
	private $aggregated_table = 'affiliate_aggregation';

	/**
	 * Source table.
	 * 
	 * @var string
	 */
	private $source_table = 'affiliates';

	/**
	 * Columns to pull data from.
	 * @var array
	 */
	private $columns = array('monthly' =>array('affiliate_id', 'revenue'));

    /**
     * Limit number of affiliates.
     * 
     * @var int
     */
    private $limit;

	/**
	 * Set number of affiliates to receive.
	 * 
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	/**
     * Get affiliate data for given day.
     *
     * @return array
     */
	public function getDailyStatistics()
	{
		
	}
		
	/**
     * Get affiliate data for the month.
     *
     * @return array
     */
	public function getMonthlyStatistics()
	{
        $affiliates[$this->columns['monthly'][0]] = DB::table($this->aggregated_table)->select($this->columns['monthly'][0])
        																			  ->orderBy('revenue','desc')
        																			  ->take($this->limit)
        																			  ->get();

        $affiliates = $this->getAffiliateDetails($affiliates);
        return $affiliates;
	}

	/**
     * Get affiliate data for the year.
     *
     * @return string
     */
	public function getYearlyStatistics()
	{
		return '2016';
	}

	/**
	 * Get affiliate details based on affiliate data.
	 * 
	 * @param  array 
	 * @return array
	 */
	public function getAffiliateDetails($affiliates)
	{
		foreach (reset($affiliates) as $affiliate) {
        	array_push($affiliates, DB::table($this->source_table)->select('affiliate_id','website', 'company')
        														  ->where('affiliate_id', '=', $affiliate->affiliate_id)
        														  ->get());
        }
     	array_shift($affiliates);
        return $affiliates;
	}
}