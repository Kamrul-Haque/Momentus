<?php

namespace App\Traits;

use App\Services\DatetimeConversionService;

/**
 *  Formats duration attribute of the model
 */
trait HasDuration
{
    /**
     * Formats the duration attribute
     *
     * @param $value
     * @return string|null
     */
    public function getDurationAttribute($value): ?string
    {
        if ($value)
            return DatetimeConversionService::convertMinutesToDiffHuman($value);

        return null;
    }
}
