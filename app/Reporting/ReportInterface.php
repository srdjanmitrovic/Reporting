<?php

namespace App\Reporting;

interface ReportInterface
{
    function getDaily($day);
    function getMonthly($month);
    function getYearly($year);
}