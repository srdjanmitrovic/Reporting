<?php 

namespace App\Reporting;

use DB;

class ReportAggregator
{

	public function __construct()
	{
		$this->transactionCount = DB::table('transactions')->count();
	}
	
	public function aggregateAverage($sum)
	{
		$average = (int)$sum/$this->transactionCount;
		return $average;
	}
}