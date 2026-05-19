<?php

namespace App\Traits;

trait hasCode
{
    public static function byCode(string $code){
        return self::query()->where('code', $code)->first();
    }
}
