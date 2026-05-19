<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscribe extends Model
{
    /** @use HasFactory<\Database\Factories\User\SubscribeFactory> */
    use HasFactory;

    ### Настройки
    ##################################################
    protected
    $table = 'main__subscribes';
     protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'division_id',
        'service_id',
        'worker_id',
        'start_at',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
        ];
    }

    ### Связи
    ##################################################
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id')->withTrashed();
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id')->withTrashed();
    }
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

}
