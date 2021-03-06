<?php 

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{

	use DatabaseTransactions;

    public function testLoginPageExists()
	{
		$this->visit('/login')->see('Login');
		$this->visit('/login')->see('Username');
		$this->visit('/login')->see('Password');
		$this->visit('/login')->see('Submit');
	}

	public function testLoginFormSuccess()
	{
		$user = factory(App\User::class)->create(['username'=>'test', 'password'=>bcrypt('test')]);
		$this->visit('/login')->type('test', 'username')->type('test', 'password')->press('Login')->seePageIs('/home');

	}

	public function testLoginFormFailure()
	{
		$this->visit('/login')->type('junk', 'username')->type('junk', 'password')->press('Login')->seePageIs('/login');
	}
}