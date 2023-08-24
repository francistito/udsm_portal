<?php

namespace App\Models\Race;

use App\Models\BaseModel;
use App\Models\Race\Attribute\RaceRegistrationAttribute;

class RaceRegistration extends BaseModel
{
    use RaceRegistrationAttribute;
    //

    protected $guarded = [];

    public static function sumColumns()
    {
        return self::sum('five_km') + self::sum('ten_km') + self::sum('twenty_one_km');
    }

}
