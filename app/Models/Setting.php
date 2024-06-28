<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    public function getPortalLogoAttribute($value): ?string
    {
        if ($value)
            return asset("storage/{$value}");

        return null;
    }
}
