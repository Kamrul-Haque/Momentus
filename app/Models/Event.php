<?php

namespace App\Models;

use App\Casts\LocalDateTime;
use App\Enums\EventStatus;
use App\Services\GenerateUniqueIdService;
use App\Traits\HasCreatedBy;
use App\Traits\HasDuration;
use App\Traits\HasIsOwner;
use App\Traits\SetOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasCreatedBy, HasIsOwner, SetOwner, HasDuration;

    protected $guarded = ['id'];

    /**
     * Generate unique reminder_id while creating new model object.
     *
     * @return void
     */
    public static function booted(): void
    {
        static::creating(function ($model) {
            $model->reminder_id = GenerateUniqueIdService::generate('EVT');
        });
    }

    /**
     * Override the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'reminder_id';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => EventStatus::class,
            'start_at' => LocalDateTime::class,
            'end_at' => LocalDateTime::class,
        ];
    }

    /**
     * Sets BelongsToMany relationship with user model
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
