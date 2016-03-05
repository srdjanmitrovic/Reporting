<?php

namespace specs\App\Reporting;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FooSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Reporting\Foo');
    }
}
