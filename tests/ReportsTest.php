<?php 

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ReportsTest extends TestCase		
{	

	use DatabaseTransactions;

	public function testReportsPageExists()
	{
		$user = factory(App\User::class)->create();

		$this->actingAs($user)
             ->withSession(['testing' => 'testing'])
             ->visit('/home')
             ->see('Dashboard');
	}

	public function testReportDataExists()
	{
		$user = factory(App\User::class)->create();

		$this->actingAs($user)
             ->withSession(['testing' => 'testing'])
             ->visit('/home')
		     ->assertViewHas('reportData');
	}
}