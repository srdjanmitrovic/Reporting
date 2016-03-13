<?php 

namespace App\Reporting;

class NetworkReport 
{
	
	private $repository;

	private $aggregator;

	function __construct(RepositoryInterface $transactionRepository, ReportAggregator $aggregator)
	{
		$this->repository = $transactionRepository;
		$this->aggregator = $aggregator;
	}

	public function getReport($date)
	{
		$this->repository->setDate($date);
		$dailyStatistics = $this->repository->getDailyStatistics();
		$monthlyStatistics = $this->repository->getMonthlyStatistics();
		$dailyStatistics = $this->aggregator->aggregateDailyValues($dailyStatistics);
		$averageMonthlyStatistics = $this->aggregator->aggregateAverage($monthlyStatistics);
		return array('monthly' => $averageMonthlyStatistics, 'daily' => $dailyStatistics);
	}
}