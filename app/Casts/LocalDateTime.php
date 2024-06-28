<?php

namespace App\Casts;

use App\Services\DatetimeConversionService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class LocalDateTime implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $timezone = (auth()->check() && auth()->user()->timezone)
            ? auth()->user()->timezone
            : config('app.timezone');

        if (!is_null($value))
            return DatetimeConversionService::convertToLocal(
                $value,
                $timezone,
                config('app.date_format'),
                config('app.time_format')
            );

        return null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $timezone = (auth()->check() && auth()->user()->timezone)
            ? auth()->user()->timezone
            : config('app.timezone');

        if (is_string($value) || $value instanceof Carbon)
            return DatetimeConversionService::convertToUTC($value, $timezone);

        return null;
    }
}
