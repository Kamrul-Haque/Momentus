<?php

namespace App\Traits;

/**
 * Sets owner user_id to the model
 */
trait SetOwner
{
    /**
     * Sets the owner attribute while creation of the model
     *
     * @return void
     */
    public static function bootSetOwner(): void
    {
        if (auth()->check())
        {
            static::created(function ($model) {
                $model->users()->sync(auth()->user()->id, false);
            });

            /*static::updated(function ($model) {
                $model->users()->sync(auth()->user()->id, false);
            });*/
        }
    }
}
