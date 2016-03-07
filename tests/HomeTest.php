<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    
    public function testHomePageExists()
    {
        $this->visit('/')->see('Your Application\'s Landing Page');
    }
    
    public function testLoginButtonShouldSendTheUserToTheLoginPage()
    {
        $this->visit('/')->click('Login')->seePageIs('/login');
    }
    
    public function testHomeButtonShouldSendTheUserToTheLoginPage()
    {
        $this->visit('/')->click('Home')->seePageIs('/login');
    }
    
    public function testLaravelButtonSendsUserToHomePage()
    {
        $this->visit('/')->click('Laravel')->seePageIs('/');
    }
}
