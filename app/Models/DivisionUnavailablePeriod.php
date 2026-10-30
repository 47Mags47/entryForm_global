<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivisionUnavailablePeriod extends Model
{
    ### Настройки
    ##################################################
    protected
    $table = 'main__division_unavailable_periods',
    $fillable = [
        'division_id',
        'date_start',
        'date_end'
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date',
            'date_end' => 'date',
        ];
    }

    ### Связи
    ##################################################
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
