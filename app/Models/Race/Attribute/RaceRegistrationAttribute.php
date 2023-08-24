<?php

namespace App\Models\Race\Attribute;

use App\Models\System\CodeValue;
use Illuminate\Support\Facades\DB;

trait RaceRegistrationAttribute
{

    public function getFullnameAttribute() {
        return proper_case_word($this->first_name) . " " . proper_case_word($this->last_name);
    }

    public function getGenderNameAttribute()
    {
        $name = code_value()->nameWithNoLang($this->gender_cv_id);
        return $name;
    }

    public function getRaceStatusAttribute()
    {
        $status = $this->status;
        switch ($status){
            case 0:
                return "<span class='badge badge-pill badge-success' data-toggle='tooltip' data-html='true' title='" . trans('label.active') . "'>" .  trans('pending') .  "</span>";


                break;
            case 1:

                return "<span class='badge badge-pill badge-success' data-toggle='tooltip' data-html='true' title='" . trans('label.active') . "'>" .  trans('paid') .  "</span>";


                break;
        }
    }


    public function getNoOfRunnersAttribute()
    {
        $runners = DB::table('race_registrations')
            ->selectRaw('SUM(five_km + ten_km + twenty_one_km) as total_runners')->where('id',$this->id)
            ->first();
    }



}
