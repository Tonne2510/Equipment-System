<?php

use App\Helpers\DateHelper;
use Carbon\Carbon;

if (!function_exists('formatRemainingTime')) {
    /**
     * Format remaining time as "X ngày Y giờ Z phút"
     */
    function formatRemainingTime($date)
    {
        return DateHelper::formatRemainingTime($date);
    }
}

if (!function_exists('formatTimeDifference')) {
    /**
     * Format time difference with sign
     */
    function formatTimeDifference($date, $isPast = false)
    {
        return DateHelper::formatTimeDifference($date, $isPast);
    }
}

if (!function_exists('remainingTime')) {
    /**
     * Get remaining time info for blade template
     */
    function remainingTime($date)
    {
        return DateHelper::remaining($date);
    }
}
