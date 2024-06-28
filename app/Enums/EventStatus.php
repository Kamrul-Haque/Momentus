<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum EventStatus: int
{
    use EnumToArray;

    case UPCOMING = 1;
    case COMPLETED = 2;
}
