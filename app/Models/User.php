<?php

namespace App\Models;

use App\Http\Resources\SubscribeTimeLineResource;
use App\Models\UserRole;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use App\Models\ChangeEmailToken;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    ### Настройки
    ##################################################
    protected
    $table = 'main__users',
    $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'division_id'
    ],
    $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    ### Методы
    ##################################################
    public function hasRole(string $role)
    {
        return user()->role_id === UserRole::where('code', $role)->get()->first()->id;
    }

    ### Связи
    ##################################################
    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function services(): HasManyThrough
    {
        return $this->hasManyThrough(Service::class, UserService::class, 'user_id', 'id', 'id', 'service_id')->withTrashed();
    }

    public function shedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class, 'user_id', 'id');
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(Subscribe::class, 'worker_id', 'id');
    }

}
