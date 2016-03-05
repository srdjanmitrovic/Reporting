<?php

namespace App\Reporting;

interface ReportInterface
{
    function getDaily();
    function getMonthly();
    function getYearly();
}