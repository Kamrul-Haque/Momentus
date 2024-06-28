<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use App\Traits\HasCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasCreatedBy;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => Role::class,
    ];

    protected $appends = ['role_name'];

    /**
     * Returns the 'role_name' of the user
     *
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        return Str::lower(str_replace('_', ' ', $this->role->name));
    }

    /**
     * Gets the image from storage
     *
     * @param $value
     * @return string|null
     */
    public function getImageAttribute($value): ?string
    {
        if ($value)
            return route('users.image', $this);

        return null;
    }

    /**
     * Checks if the user has access by the given role
     *
     * @param int $role
     * @return bool
     */
    public function hasAccess(int $role): bool
    {
        return auth()->user()->role->value <= $role;
    }
}
