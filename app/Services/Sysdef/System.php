<?php

namespace App\Services\Sysdef;

use App\Models\System\Sysdef;
use App\Repositories\Setting\OrganizationRepository;
use App\Repositories\System\JobManageRepository;
use App\Repositories\System\SysdefRepository;
use App\Services\Access\SystemSecurity\DatabaseInformation;
use App\Services\Licensing\Traits\AppLicensing;


class System
{
    use  DatabaseInformation;

    public function __invoke($reference)
    {
        return  Sysdef::query()->where('reference', $reference)->first();
    }

    /*Get specified data using reference. Return casted value with data type*/
    public function data($reference)
    {
        $def = Sysdef::query()->where('reference', $reference)->first();
        $value = $this->castValue($def->value, $def->data_type) ;

        /*Overwrite*/
        switch ($reference)
        {
            case 'FAAVACCONT'://Accounting Management
                return $this->licensedModules()['accounting'];
                break;
            default:
                return $value;
                break;
        }
    }

    /*Return sysdef model using reference*/
    public function definition($reference)
    {
        return Sysdef::query()->where('reference', $reference)->first();
    }

    /*Cast value to their respective data type*/
    public function castValue($value, $data_type)
    {
        switch ($data_type){
            case 'integer':
                return (int)($value);
                break;
            case 'smallInteger':
                return (int)($value);
                break;
            case 'numeric':
                return (double)($value);
                break;
            case 'string':
                return ($value);
                break;
            case 'string_long':
                return ($value);
                break;
            case 'text':
                return ($value);
                break;
            case 'boolean':
                return $value == 'true' ? true : false;
                break;
            case 'date':
                return $value;
                break;
            case 'ckeditor':
                return $value;
                break;
            default:
                return ($value);
                break;
        }

    }


    /*Data formatted presentation*/
    public function dataFormatted($reference)
    {
        $def = Sysdef::query()->where('reference', $reference)->first();
        return $this->valueFormatted($def->value, $def->data_type) ;
    }
    /*Cast value to their respective data type*/
    public function valueFormatted($value, $data_type)
    {
        $value = $this->castValue($value, $data_type);

        switch($data_type){
            case 'date':
                return short_date_format($value);
                break;
            case 'boolean':
                return boolean_badge($value);
                break;
            default:
                return $value;
                break;
        }

    }

    /*Get doc full path url for images*/
    public function getDocFullPathUrl($reference)
    {
        $sysdef = $this->definition($reference);
        return $sysdef->getDocFullPathUrlAttribute();
    }



    /*Pending Jobs count*/
    public function pendingJobsCount()
    {
        $count = (new JobManageRepository())->countJobs();
        return $count;
    }


    /*Find psms organization*/
    public function findPsmsOrganization()
    {
        return (new OrganizationRepository())->findPsmsOrganization();
    }


    /**
     * @param $reference
     * @param $dae
     * Update value by reference
     */
    public function updateValueByReference($reference, $value)
    {
        $sysdef = (new SysdefRepository())->query()->where('reference', $reference)->update([
            'value' => $value
        ]);
        return $sysdef;
    }


    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     * New app update alert
     */
    public function newAppUpdateAlert()
    {
        $check_if_can_alert = (new SysdefRepository())->checkIfThereIsNewAppUpdateForAlert();

        if($check_if_can_alert){
            return __('alert.system.sysdef.new_app_updates');
        }else{
            return null;
        }
    }

}
