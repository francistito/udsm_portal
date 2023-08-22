<?php

namespace App\Services\Access;

use App\Exceptions\GeneralException;
use App\Repositories\Operation\Station\FuelTankRepository;
use App\Repositories\Operation\Stock\ProductGodownRepository;
use App\Repositories\Operation\Stock\ProductStoreRepository;
use App\Repositories\Workflow\WfTrackRepository;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class Access.
 */
class Access
{
    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return auth()->user();
    }

    /**
     * Return if the current session user is a guest or not.
     *
     * @return mixed
     */
    public function guest()
    {
        return auth()->guest();
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        return auth()->logout();
    }

    /**
     * Get the currently authenticated user's id.
     *
     * @return mixed
     */
    public function id()
    {
        return auth()->id();
    }

    /**
     * @param Authenticatable $user
     * @param bool            $remember
     */
    public function login(Authenticatable $user, $remember = false)
    {
        $logged_in = auth()->login($user, $remember);
        return $logged_in;
    }

    /**
     * Check whether user is authenticated or not ...
     *
     * @return bool
     */
    public function check()
    {
        return auth()->check();
    }

    public function viaRemember()
    {
        return auth()->viaRemember();
    }


    /**
     * @return mixed
     */
    public function getWorkflowPendingCount()
    {
        return (new WfTrackRepository())->getPendingCount();
    }


    public function getWorkflowPendingForStationStaffCount()
    {
        return (new WfTrackRepository())->getPendingForStationStaffCount();
    }

    /**
     * @return mixed
     */
    public function getMyWorkflowPendingCount()
    {
        return (new WfTrackRepository())->getMyPendingCount();
    }

    /*Get Alert Monitor Count*/
    public function getAllAlertMonitorCount()
    {
        $godown_count = (new ProductGodownRepository())->getProductGodownForAlertMonitorCount();
        $store_count  = (new ProductStoreRepository())->getAllProductStoreForAlertMonitorCount();
        $fuel_tank_count = (new FuelTankRepository())->getFuelTankForAlertMonitorCount();
        return $godown_count + $store_count + $fuel_tank_count  ;
    }


    /**
     * Check if user has a default workflow module defined by a workflow group
     * @param $wf_module_group_id
     * @param $level
     * @param array $param
     * @return bool
     * @throws GeneralException
     */
    public function hasWorkflowDefinition($wf_module_group_id, $level, array $param = [])
    {
        $return = false;
        if ($user = $this->user()) {
            $return = $user->hasWorkflowDefinition($wf_module_group_id, $level);
        }
        if (!$return) {
            throw new GeneralException(trans('auth.workflow_error'));
        }
        return $return;
    }

    /**
     * Check if user has a specific workflow module specified by workflow module group and type
     * @param $wf_module_group_id
     * @param $type
     * @param $level
     * @return bool
     * @throws GeneralException
     */
    public function hasWorkflowModuleDefinition($wf_module_group_id, $type, $level)
    {
        $return = false;
        if ($user = $this->user()) {
            $return = $user->hasWorkflowModuleDefinition($wf_module_group_id, $type, $level);
        }
        if (!$return) {
            throw new GeneralException(trans('auth.workflow_error'));
        }
        return $return;
    }


    /**
     * Return all users
     * @return array
     */
    public function allUsers()
    {
        $return = [];
        $user = $this->user();
//        if ($this->substitutingCount()) {
//            $return = $user->substitutingUsers()->select(['id'])->pluck("id")->toArray();
//        }
        $return[] = $this->id();
        return $return;
    }


    /**
     * Check if the current user has a permission by its name or id.
     *
     * @param string $permission Permission name or id.
     *
     * @return bool
     */
    public function allow($permission)
    {
        $return = false;
        if ($user = $this->user()) {
            $return = $user->allow($permission);
        }else{
            /*Allow guest*/

        }

        return $return;
    }


}
