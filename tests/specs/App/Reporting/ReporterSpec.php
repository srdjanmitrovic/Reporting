<?php

namespace specs\App\Reporting;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use App\Reporting\TransactionsReport;

class ReporterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Reporting\Reporter');
    }

    function it_calls_the_reporting_model(TransactionsReport $report)
    {
    	$date = array('day'=> '02', 'month' => '02');
    	$report->generateReport($date)->shouldBeCalled()->willReturn(array('day'=>array('count'=>4168), 'month'=>array('count'=>253873), 'year'=>'2016'));
    	$this->getReport($report, $date)->shouldReturn(array('day'=>array('count'=>4168), 'month'=>array('count'=>253873), 'year'=>'2016'));
    }
}
