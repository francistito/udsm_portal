<?php

namespace App\Models\Access\Attribute;

use App\Models\Payment\Invoice;
use App\Models\Product\Customer;
use App\Repositories\Access\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait UserAttribute
{

    /*Get full name attribute*/
    public function getFullnameAttribute()
    {
        $return = $this->username;
        if(isset($this->employee_id))
        {
            $return = $this->employee->fullname ?? $return;
        }
        return $return;
    }


    public function getCreatedAtFormattedAttribute()
    {
        return  Carbon::parse($this->created_at)->format('d-M-Y');
    }

    public function getLastLoginFormattedAttribute()
    {
        return  Carbon::parse($this->last_login)->format('d-M-Y');
    }



    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isactive == true;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    public function getOtpConfirmedFormattedAttribute()
    {
        return sysdef()->data('OTPVERIF') ?  $this->otp_confirmed : true;
    }

    /*Check if can manage project*/
    public function checkIspm($project)
    {
        $team = $project->teams()->where('user_id', $this->id)->first();
        if($team){
            if ($team->pivot->ispm == 1 || $team->pivot->can_manage_project == 1) {
                return true;
            }
        }

        return false;
    }

    /* Active status label*/
    public function getActiveStatusLabelAttribute()
    {
        if ($this->isactive == 1) {
            return "<span class='badge badge-pill badge-success' data-toggle='tooltip' data-html='true' title='" . trans('label.active') . "'>" . trans('label.active') . "</span>";
        } else {
            return "<span class='badge badge-pill badge-warning' data-toggle='tooltip' data-html='true' title='" . trans('label.inactive') . "'>" . trans('label.inactive') . "</span>";
        }
    }

    /*Get Roles of the users*/

    public function getRoleLabelAttribute() {
        $roles = [];
        if ($this->roles()->count() > 0) {
            foreach ($this->roles as $role) {
                array_push($roles, $role->name);
            }
            return implode(", ", $roles);
        } else {
            return '<span class="tag tag-danger">'. trans('label.none') . '</span>';
        }
    }

    /*Workflow resource name*/
    public function getResourceNameAttribute()
    {
        return $this->username;
    }

    /*Auditable name for audit*/
    public function getAuditableNameAttribute()
    {
        return  $this->username;
    }

    /*employee name*/
    public function getEmployeeNameAttribute()
    {
        if ($this->employee_id)
        {
            return  $this->employee->fullname;

        }else
        {
            return '' ;
        }
    }


    /*Get station*/
    public function getStationAttribute()
    {
        $station = null;

        if($this->employee_id){

            $check = $this->employee->stations()->count();

            if($check > 0){
                $station = $this->employee->stations()->first();
            }
        }

        return $station;
    }



    public function getStationIdAttribute()
    {
        $station = $this->getStationAttribute();
        $station_id = null;
        if($station)
        {
            $station_id = $station->id;
        }

        return $station_id;
    }


    public function allCustomerItems()
    {
        $customers = Customer::where('user_id',$this->id)->get();
        return $customers;
    }


    //get pending invoices
    public function getPendingInvoices()
    {
        $customer = Customer::where('user_id',$this->id)->first();
        $invoices = [];
        if ($customer)
        {
            $invoices = Invoice::where('customer_id',$customer->id)->where('ispaid',0)->where('iscancelled',0)->get();

        }
        return $invoices;
    }



    /**
     * @param $flag_index
     * @return bool
     * Get Personalized flags
     */
    public function getPersonalizedFlagValue($flag_index)
    {
       if($this->personalized_flags){
           $flags = json_decode($this->personalized_flags, true);

               return $flags[$flag_index] ?? false;
       }else{
           /*save default*/
           (new UserRepository())->updatePersonalizedFlags($this->id, 'pos_image', true);
           return false;
       }
    }

}
