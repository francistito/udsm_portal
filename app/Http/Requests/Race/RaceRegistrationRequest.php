<?php

namespace App\Http\Requests\Race;

use App\Http\Requests\Request;
use App\Models\System\Country;
use Illuminate\Validation\Rule;

class RaceRegistrationRequest extends Request
{
    public function authorized()
    {
        return true;
    }

    public function Rules()
    {
        $input = $this->all();
        $basic = [];
        $optional = [];
        $array = [];
        $basic = [
            "first_name" => "required",
            "last_name" => "required",
            "date_of_birth" => "required",
            "gender_cv_id" => "required",
            'phone_number' => 'nullable',
            "email" => "required",
            "nationality" => "nullable",
            "address" => "nullable",
            "race_type_cv_id" => "required",
            "terms" => "required",
            "team_name" => "nullable",

        ];


        $race_type = $input['race_type_cv_id'];
        switch ($race_type){
            case 8:
                $optional=[
                    "race_category_cv_id" => "required",
                    "tshirt_type_cv_id" => "required",
                    "tshirt_size_cv_id" => "required",
                ];

                break;
            case 9:
                $optional=[

                ];
                break;

        }

        return array_merge( $basic, $optional);
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['email'] = isset($input['email']) ? strtolower(trim($input['email'])) : null;
        $input['date_of_birth'] = isset($input['date_of_birth']) ? standard_date_format($input['date_of_birth']) : null;

        $this->replace($input);
        return $this->all();
    }
}
