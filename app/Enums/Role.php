<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum Role: int
{
    use EnumToArray;

    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case USER = 3;
}
