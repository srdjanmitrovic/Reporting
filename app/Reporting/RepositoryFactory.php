<?php 

namespace App\Reporting;

class RepositoryFactory 
{

    /**
     * Used to store the different repositories.
     * 
     * @var array $repositoryList
     */
	private $repositoryList;

    /**
     * Create a new RepositoryFactory instance.
     */
	public function __construct()
    {
        $this->repositoryList = array('transaction' => __NAMESPACE__.'\TransactionRepository',
                                      'affiliate' => __NAMESPACE__.'\AffiliateRepository',);
    }

    /**
     * Returns new repository depending on request.
     * 
     * @param  string $class    
     * @param  string $argument used to pass the repository class it's arguments 
     * @return mixed
     */
	public function getRepository($class, $argument = '')
	{
		if (!array_key_exists($class, $this->repositoryList)) {
            throw new \InvalidArgumentException($class . ' is not a valid repository');
        }
        $className = $this->repositoryList[$class];
        return new $className($argument);
	}
}