<?php

declare(strict_types=1);

// Date helper functions (extend as needed).

if (!function_exists('format_date')) {
    /**
     * Format date for display (extend with your locale/format).
     */
    function format_date(?string $date, string $format = 'Y-m-d'): string
    {
        if ($date === null || $date === '') {
            return '';
        }

        $ts = strtotime($date);

        return $ts !== false ? date($format, $ts) : '';
    }
}
