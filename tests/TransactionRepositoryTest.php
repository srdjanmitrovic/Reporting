<?php 

use DB;
use App\Reporting\TransactionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionRepositoryTest extends TestCase
{

	use DatabaseTransactions;

	private $date = array('month'=>03, 'day'=>05);
	
	public function setUp()
	{
		$this->repository = new TransactionRepository($this->date);
		parent::setUp();
	}

	public function testThatDateCanBeSet()
	{	
		$this->assertEquals($this->date, $this->repository->date);
	}

	public function testGettingAggregatedValues()
	{
		DB::table('transaction_aggregation')->insert(['day'=>03, 'month'=>05, 'last_transaction_id'=>100, 'commission_average'=>5, 'commission_sum'=>100, 'sale_average'=>35, 'sale_sum'=>197,'transaction_count'=>35]);
		// $this->assertEquals(array('month'=>03, 'day'=>5), $this->repository->date);
	}
}