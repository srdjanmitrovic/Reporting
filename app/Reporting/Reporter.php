<?php

namespace App\Reporting;

class Reporter
{

    /**
     * @param  ReportInteface $report
     * @param  Array $date
     * @return Array
     */
    public function getReport(ReportInterface $report, $date)
    {
        $daily   = $report->getDaily($date['day']);
        $monthly = $report->getMonthly($date['month']);
        $yearly  = $report->getYearly('test');
        return array(
            'day' => $daily,
            'month' => $monthly,
            'year' => $yearly
        );
    }
}