<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWeekends extends Model
{
    ### Настройки
    ##################################################
    protected
    $table = 'main__user_weekends',
    $fillable = [
        'user_id',
        'replacement_id',
        'division_id',
        'allow_meeting',
        'date_start',
        'date_end'
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
            'allow_meeting' => 'boolean'
        ];
    }

    ### Методы
    ##################################################

    ### Связи
    ##################################################
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replacement(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replacement_id');
    }
}
