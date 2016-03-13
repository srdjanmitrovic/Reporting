<?php 

namespace App\Reporting;

class RepositoryFactory 
{

	private $repositoryList;

	public function __construct()
    {
        $this->repositoryList = array(
            'transaction' => __NAMESPACE__.'\TransactionRepository',
            'affiliate' => __NAMESPACE__.'\AffiliateRepository',
        );
    }

	public function getRepository($class, $argument = '')
	{
		if (!array_key_exists($class, $this->repositoryList)) {
            throw new \InvalidArgumentException($class . ' is not a valid repository');
        }
        $className = $this->repositoryList[$class];
        return new $className($argument);
	}
}