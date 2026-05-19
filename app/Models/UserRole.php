<?php

namespace App\Models;

use App\Traits\hasCode;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use hasCode;

    ### Настройки
    ##################################################
    protected
        $table = 'glossary__user_roles';
}
