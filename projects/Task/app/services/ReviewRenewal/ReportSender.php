<?php namespace App\Services\ReviewRenewal;

/**
 * Interface ReportSender
 * @package App\Services\ReviewRenewal
 */
interface ReportSender
{
    /**
     * Send report.
     *
     * @param $iteration
     */
    public function report($iteration);
}
