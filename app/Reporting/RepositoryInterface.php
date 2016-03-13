<?php

namespace App\Reporting;

interface RepositoryInterface
{
	function getDailyStatistics();
	function getMonthlyStatistics();
	function getYearlyTransactionStatistics();
}