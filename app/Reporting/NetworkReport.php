<?php 

namespace App\Reporting;

class NetworkReport 
{
	/**
	 * Repository instance.
	 * 
	 * @var RepositoryInterface
	 */
	private $repository;

	/**
	 * Aggregator instance.
	 * 
	 * @var ReportAggregator
	 */
	private $aggregator;

	/**
	 * Create a new NetworkReport instance.
	 * 
	 * @param RepositoryInterface $transactionRepository 
	 * @param ReportAggregator    $aggregator           
	 */
	function __construct(RepositoryInterface $transactionRepository, ReportAggregator $aggregator)
	{
		$this->repository = $transactionRepository;
		$this->aggregator = $aggregator;
	}

	/**
	 * Assemble report before returning to the controller.
	 * 
	 * @param  array $date 
	 * @return array
	 */
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