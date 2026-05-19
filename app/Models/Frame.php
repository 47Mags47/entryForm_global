<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Division;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Frame extends Model
{
    protected
    $table = 'main__frames',
    $fillable = [
        'token',
        'division_id',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
