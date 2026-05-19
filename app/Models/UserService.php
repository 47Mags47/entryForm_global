<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserService extends Model
{
    ### Настройки
    ##################################################
    protected
    $table = 'main__user_service',
    $fillable = [
        'user_id',
        'service_id',
    ];

    ### Связи
    ##################################################
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
