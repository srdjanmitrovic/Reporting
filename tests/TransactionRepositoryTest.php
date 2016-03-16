<?php 

use App\Reporting\TransactionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionRepositoryTest extends TestCase
{
	/**
	 * To roll back database after inserting data.
	 */
	use DatabaseTransactions;

	/**
	 * Date for transaction set up.
	 * 
	 * @var array $date
	 */
	private $date = array('month'=>03, 'day'=>05);

	/**
	 * To store data from the aggregation table.
	 * 
	 * @var array stdClass
	 */
	private $results;
	

	/**
	 * Set up function for test.
	 * 
	 * @return void
	 */
	public function setUp()
	{
		$this->repository = new TransactionRepository($this->date);
		parent::setUp();
		DB::table('transaction_aggregation')->insert(['day'=>03, 'month'=>05, 'last_transaction_id'=>100, 'commission_average'=>5, 'commission_sum'=>100, 'sale_average'=>35, 'sale_sum'=>197,'transaction_count'=>35]);
		$results = DB::table('transaction_aggregation')->select()->get();
		$this->results = reset($results);
	}

	/**
	 * Test that date is set.
	 * 
	 * @return void
	 */
	public function testThatDateCanBeSet()
	{	
		$this->assertEquals($this->date, $this->repository->date);
	}

	/**
	 * Test that passed aggregated values are correct.
	 * 
	 * @return void
	 */
	public function testLastTransaction()
	{
		$this->assertEquals(100, $this->results->last_transaction_id);
	}

	public function testCommissionAverage()
	{
		$this->assertEquals(5, $this->results->commission_average);
	}

	public function testCommissionSum()
	{
		$this->assertEquals(100, $this->results->commission_sum);
	}

	public function testSaleAverage()
	{
		$this->assertEquals(35, $this->results->sale_average);
	}

	public function testSaleSum()
	{
		$this->assertEquals(197, $this->results->sale_sum);
	}

	public function testTransactionCount()
	{
		$this->assertEquals(35, $this->results->transaction_count);
	}
}