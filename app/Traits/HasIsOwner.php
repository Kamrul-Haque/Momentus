<?php

namespace App\Traits;

/**
 *  Adds Is Owner Attribute to Model
 */
trait HasIsOwner
{
    /**
     * initializes the trait
     *
     * @return void
     */
    protected static function bootHasIsOwner(): void
    {
        $class = static::class;
        $method = "initializeHasIsOwner";

        static::$traitInitializers[$class][] = $method;

        static::$traitInitializers[$class] = array_unique(
            static::$traitInitializers[$class]
        );
    }

    /**
     * Appends 'is_owner' attribute to parent class
     *
     * @return array
     */
    protected function initializeHasIsOwner(): array
    {
        $this->appends = array_unique(array_merge($this->appends, ['is_owner']));

        return parent::getArrayableAppends();
    }

    /**
     * checks if the authenticated user is the owner of the record
     *
     * @return bool
     */
    public function getIsOwnerAttribute(): bool
    {
        return $this->created_by_id === auth()->user()->id;
    }
}
