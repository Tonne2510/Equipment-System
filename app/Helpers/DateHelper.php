<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format remaining time as "X ngày Y giờ Z phút"
     */
    public static function formatRemainingTime($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = now();
        $diff = $date->diff($now);

        $days = abs($diff->days);
        $hours = $diff->h;
        $minutes = $diff->i;

        $parts = [];

        if ($days > 0) {
            $parts[] = "{$days} ngày";
        }

        if ($hours > 0) {
            $parts[] = "{$hours} giờ";
        }

        if ($minutes > 0 || empty($parts)) {
            $parts[] = "{$minutes} phút";
        }

        return implode(' ', $parts);
    }

    /**
     * Format time difference with sign
     */
    public static function formatTimeDifference($date, $isPast = false)
    {
        $formatted = self::formatRemainingTime($date);
        
        if ($isPast) {
            return "-{$formatted}";
        }

        return $formatted;
    }

    /**
     * Get remaining time for blade template
     */
    public static function remaining($date)
    {
        $now = now();
        
        if ($date < $now) {
            return [
                'text' => self::formatRemainingTime($date),
                'isPast' => true,
                'status' => 'danger'
            ];
        }

        return [
            'text' => self::formatRemainingTime($date),
            'isPast' => false,
            'status' => 'info'
        ];
    }
}
