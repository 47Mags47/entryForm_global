<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    /** @use HasFactory<\Database\Factories\Admin\DivisionFactory> */
    use HasFactory, SoftDeletes;

    ### Настройки
    ##################################################
    protected
    $table = 'main__divisions';

    protected $fillable = [
        'name',
        'address',
        'city_id',
        'group_id'
    ];

    ### Связи
    ##################################################
    /**
     * Get the user that owns the Division
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
        public function group(): BelongsTo
    {
        return $this->belongsTo(DivisionGroup::class, 'group_id');
    }
}
