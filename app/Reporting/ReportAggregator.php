<?php 

namespace App\Reporting;

use DB;

class ReportAggregator
{

	private $transactionCount;

	public function __construct()
	{
		$this->transaction_count = DB::table('transactions')->count();
	}
	
	public function aggregateAverage($values)
	{
		foreach($values as $key=>$value){
			$averageValue = (int)$value/$this->transaction_count;
			$values[$key.'_average'] = $averageValue; 
		}
		$values['transaction_count'] = $this->transaction_count;
		return $values;
	}

	public function aggregateDailyValues($values)
	{
		foreach($values as $key=>$value){
			if(empty($value)){
				$values[$key] = 0;
			}else{
				$values[$key] = reset($value[0]);
			}
		}
		return $values;
	}
}