<?php

namespace App\Models\Race;

use App\Models\BaseModel;

class RaceRegistration extends BaseModel
{
    //

    protected $guarded = [];

    public static function sumColumns()
    {
        return self::sum('five_km') + self::sum('ten_km') + self::sum('twenty_one_km');
    }

}
