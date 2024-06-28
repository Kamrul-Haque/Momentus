<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        $array = [];

        foreach (self::cases() as $case)
        {
            $array[] = (object)['name' => Str::lower(str_replace('_', ' ', $case->name)), 'value' => $case->value];
        }

        return $array;
    }
}
