<?php

namespace App\Models;

use App\Casts\LocalDateTime;
use App\Enums\EventStatus;
use App\Observers\EventObserver;
use App\Traits\HasCreatedBy;
use App\Traits\HasDuration;
use App\Traits\HasIsOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[ObservedBy([EventObserver::class])]
class Event extends Model
{
    use HasFactory, SoftDeletes, HasCreatedBy, HasIsOwner, HasDuration;

    protected $guarded = ['id'];
    protected $appends = ['status', 'status_name'];

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
            'start_at' => LocalDateTime::class,
            'end_at' => LocalDateTime::class,
        ];
    }

    public function getStatusAttribute(): EventStatus
    {
        if ($this->deleted_at)
            return EventStatus::ARCHIVED;

        if (Carbon::parse($this->getRawOriginal('start_at'))->isPast())
            return EventStatus::COMPLETED;

        return EventStatus::UPCOMING;
    }

    public function getStatusNameAttribute(): string
    {
        return Str::lower($this->status->name);
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
