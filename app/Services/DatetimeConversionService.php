<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DatetimeConversionService
{
    /**
     * Converts 'UTC' timestamp to local timestamp or format
     *
     * @param Carbon|string $dateTime datetime string or timestamp
     * @param string $localTimezone PHP timezone
     * @param string|null $dateFormat date format
     * @param string|null $timeFormat time format
     * @return array|Carbon
     */
    public static function convertToLocal(Carbon|string $dateTime, string $localTimezone, string $dateFormat = null, string $timeFormat = null): array|Carbon
    {
        $carbonDateTime = $dateTime instanceof Carbon
            ? $dateTime
            : Carbon::parse($dateTime, 'UTC')->setTimezone($localTimezone);

        if ($dateFormat && $timeFormat)
            return [
                'raw_time' => $carbonDateTime->toTimeString(),
                'raw_date' => $carbonDateTime->toDateString(),
                'raw_date_time' => $carbonDateTime->toDateTimeString(),
                'diff' => $carbonDateTime->diffForHumans(now(), CarbonInterface::DIFF_ABSOLUTE),
                'time' => $carbonDateTime->format($timeFormat),
                'date' => $carbonDateTime->format($dateFormat),
                'date_time' => $carbonDateTime->format($dateFormat) . ', ' . $carbonDateTime->format($timeFormat)
            ];

        if ($dateFormat)
            $carbonDateTime->format($dateFormat);

        if ($timeFormat)
            $carbonDateTime->format($timeFormat);

        return $carbonDateTime;
    }

    /**
     * Converts local timestamp to 'UTC' timestamp or format
     *
     * @param Carbon|string $dateTime datetime string or timestamp
     * @param string $localTimeZone
     * @param string|null $dateFormat date format
     * @param string|null $timeFormat time format
     * @return array|Carbon
     */
    public static function convertToUTC(Carbon|string $dateTime, string $localTimeZone, string $dateFormat = null, string $timeFormat = null): array|Carbon
    {
        $carbonDateTime = $dateTime instanceof Carbon
            ? $dateTime
            : Carbon::create($dateTime, $localTimeZone)
                    ->setTimezone('UTC');

        if ($dateFormat && $timeFormat)
            return [
                'raw_time' => $carbonDateTime->toTimeString(),
                'raw_date' => $carbonDateTime->toDateString(),
                'raw_date_time' => $carbonDateTime->toDateTimeString(),
                'diff' => $carbonDateTime->diffForHumans(now(), CarbonInterface::DIFF_ABSOLUTE),
                'time' => $carbonDateTime->format($timeFormat),
                'date' => $carbonDateTime->format($dateFormat),
                'date_time' => $carbonDateTime->format($timeFormat) . ', ' . $carbonDateTime->format($dateFormat)
            ];

        if ($dateFormat)
            $carbonDateTime->format($dateFormat);

        if ($timeFormat)
            $carbonDateTime->format($timeFormat);

        return $carbonDateTime;
    }

    /**
     * Converts minutes to hours and minutes
     *
     * @param int $minutes
     * @return string
     */
    public static function convertMinutesToDiffHuman(int $minutes): string
    {
        $options = [
            'join' => ' ',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
            'short' => true
        ];

        $start = now();
        $end = now()->addMinutes($minutes);

        return $end->diffForHumans($start, $options);
    }
}
