<?php

namespace App\Reporting;

class Reporter
{

    /**
     * @param  ReportInteface $report
     * @param  array $date
     * @return array
     */
    public function getReport(ReportInterface $report, $date)
    {
        return $report->generateReport($date);
    }
}