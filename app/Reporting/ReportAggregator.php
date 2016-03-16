<?php 

namespace App\Reporting;

class ReportAggregator
{
	
	/**
	 * Total transaction number.
	 * 
	 * @var int
	 */
	private $transactionCount;
	
	/**
	 * Aggregate average values based on total transactions and sums on the fly.
	 * 
	 * @param  array $values 
	 * @return array
	 */
	public function aggregateAverage($values)
	{
		$transaction_count = $values['transaction_count'];
		if ($transaction_count == 0) {
			foreach ($values as $key=>$value) {
				$averageValue = 0;
				$values[$key.'_average'] = 0; 
			}
			return $values;
		}
		foreach ($values as $key=>$value) {
			$averageValue = (int)$value/$transaction_count;
			$values[$key.'_average'] = $averageValue; 
		}
		$values['transaction_count'] = $transaction_count;
		return $values;
	}

	/**
	 * Parse values based on keys and values.
	 * 
	 * @param  array $values 
	 * @return array
	 */
	public function aggregateMultipleColumns($values)
	{
		foreach ($values as $key=>$value) {
			if (empty($value)) {
				$values[$key] = 0;
			} else {
				$values[$key] = reset($value[0]);
			}
		}
		return $values;
	}

	/**
	 * Parse values based on stdClass parameters.
	 * 
	 * @param  array $values 
	 * @return array
	 */
	public function aggregateSingleColumn($values)
	{
		return array('top_affiliates'=>$values);
	}
}