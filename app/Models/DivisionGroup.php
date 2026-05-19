<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DivisionGroup extends Model
{
    /** @use HasFactory<\Database\Factories\Admin\CityFactory> */
    use HasFactory;

    ### Настройки
    ##################################################
    protected
    $table = 'main__division_groups',
    $fillable = [
        'name',
    ];

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class, 'group_id');
    }

}

