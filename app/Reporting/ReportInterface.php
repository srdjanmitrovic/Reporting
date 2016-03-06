<?php

namespace App\Reporting;

interface ReportInterface
{
	function generateReport($date);
}