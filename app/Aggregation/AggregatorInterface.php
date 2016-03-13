<?php 

namespace App\Aggregation;

interface AggregatorInterface
{
	function setSourceTable($table, $month = '', $year = '');
	function setAggregationTable($table, $month = '', $year = '');
	function updateAggregationTable();
	function parseResults($results);
}