<?php 

namespace App\Reporting;

class NetworkReport 
{
	/**
	 * Repository instance.
	 * 
	 * @var RepositoryInterface
	 */
	private $factory;

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
	function __construct(RepositoryFactory $factory, ReportAggregator $aggregator)
	{
		$this->factory = $factory;
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
		$affiliateRepository = $this->factory->getRepository('affiliate');
		$transactionRepository = $this->factory->getRepository('transaction', $date);

		$dailyStatistics = $transactionRepository->getDailyStatistics();
		$monthlyStatistics = $transactionRepository->getMonthlyStatistics();

		$dailyStatistics = $this->aggregator->aggregateMultipleColumns($dailyStatistics);
		$monthlyStatistics = $this->aggregator->aggregateAverage($monthlyStatistics);

		$affiliateRepository->setLimit(5);
		$monthlyAffiliateStatistics = $affiliateRepository->getMonthlyStatistics();
		$monthlyAffiliateStatistics = $this->aggregator->aggregateSingleColumn($monthlyAffiliateStatistics);
		$monthlyStatistics = array_merge($monthlyAffiliateStatistics, $monthlyStatistics);

		return array('monthly' => $monthlyStatistics, 'daily' => $dailyStatistics);
	}
}