<?php

namespace App\Services\Workflow;

use App\Events\Sockets\BroadcastWorkflowUpdated;
use App\Exceptions\WorkflowException;
use App\Jobs\Notifications\SendSms;
use App\Models\Auth\User;
use App\Models\Hr\Employee\Employee;
use App\Models\Workflow\WfDefinition;
use App\Models\Workflow\WfTrack;
use App\Notifications\GeneralEmailNotifier;
use App\Repositories\Hr\Employee\EmployeeBulkRecoveryApprovalRepository;
use App\Repositories\Hr\Payroll\MidMonthPayrollRepository;
use App\Repositories\Hr\Payroll\PayrollApprovalRepository;
use App\Repositories\Hr\StaffLeave\StaffLeaveRepository;
use App\Repositories\Hr\Employee\Allowance\EmployeeAllowanceRepository;
use App\Repositories\Hr\Employee\Deduction\EmployeeDeductionRepository;
use App\Repositories\Hr\Employee\Loan\EmployeeLoanApprovalRepository;
use App\Repositories\Operation\Sales\PurchaseOrderRepository;
use App\Repositories\Operation\Sales\SaleRepository;
use App\Repositories\Operation\Sales\ShiftRepository;
use App\Repositories\Operation\Stock\ProductDistributionRepository;
use App\Repositories\Operation\Stock\ProductMovementRepository;
use App\Repositories\Operation\Stock\StockAdjustmentRepository;
use App\Repositories\Workflow\WfModuleGroupRepository;
use App\Repositories\Workflow\WfModuleRepository;
use App\Repositories\Workflow\WfTrackRepository;
use App\Repositories\Workflow\WfDefinitionRepository;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class Workflow
 *
 * Class to process all application workflows at different levels. It controls swiftly
 * the process of forwarding the application process and handle the proper completion of
 * each workflow.
 *
 * @author     Erick Chrysostom <e.chrysostom@nextbyte.co.tz>
 * @category
 * @package    App\Services\Workflow
 * @subpackage None
 * @copyright  Copyright (c) Nextbyte ICT Solutions
 * @license    Not Applicable
 * @version    Release: 1.02
 * @link       None
 * @since      Class available since Release 1.0.0
 */

class Workflow
{

    /**
     * @var
     */
    private $level;

    /**
     * @var
     */
    private $wf_module_group_id;

    /**
     * @var
     */
    public $wf_module_id;

    /**
     * @var
     */
    private $resource_id;

    /**
     * @var
     */
    public $wf_definition_id;

    /**
     * @var WfDefinitionRepository
     */
    private $wf_definition;

    /**
     * @var WfModuleRepository
     */
    private $wf_module;

    /**
     * @var WfTrackRepository
     */
    private $wf_track;


    /**
     * Workflow constructor.
     * @param array $input
     * @throws GeneralException
     */
    public function __construct(array $input)
    {
        /** sample 1 $input : ['wf_module_group_id' => 4, 'resource_id' => 1] **/
        /** sample 2 $input : ['wf_module_group_id' => 3, 'resource_id' => 1, 'type' => 4] **/
        /** sample 3 $input : ['wf_module_group_id' => 3] **/
        /** sample 4 $input : ['wf_module_group_id' => 3, 'type' => 4] **/
        $this->wf_definition = new WfDefinitionRepository();
        $this->wf_module = new WfModuleRepository();
        $this->wf_track = new WfTrackRepository();
        $this->resource_id = (isset($input['resource_id']) ? $input['resource_id'] : null);
        foreach ($input as $key => $value) {
            switch ($key) {
                case 'wf_module_group_id':
                    $this->wf_module_group_id = $input['wf_module_group_id'];
                    $this->wf_module_id = $this->wf_module->getModule($input);
                    break;
                case 'wf_module_id':
                    $wf_module_id = $value;
                    $wf_module = $this->wf_module->find($wf_module_id);
                    $this->wf_module_id = $wf_module_id;
                    $this->wf_module_group_id = $wf_module->wf_module_group_id;
                    break;
            }
        }
        /**
         * Get current values .....
         */
        $this->wf_definition_id = $this->wf_definition->getDefinition($this->wf_module_id, $this->resource_id);
    }

    /**
     * @return null
     */
    public function nextLevel()
    {
        return $this->wf_definition->getNextLevel($this->wf_definition_id);
    }

    /**
     * @return null
     */
    public function prevLevel()
    {
        return $this->wf_definition->getPrevLevel($this->wf_definition_id);
    }

    /**
     * @return mixed
     */
    public function lastLevel()
    {
        return $this->wf_definition->getLastLevel($this->wf_module_id);
    }

    /**
     * @return mixed
     */
    public function currentLevel()
    {
        $wf_definition = $this->wf_definition->getCurrentLevel($this->wf_definition_id);
        return $wf_definition->level;
    }

    /**
     * @return mixed
     */
    public function previousLevels()
    {
        $levels = $this->wf_definition->getPreviousLevels($this->wf_definition_id);
        return $levels;
    }

    public function getModule()
    {
        return $this->wf_module_id;
    }



    /**
     * @param $sign
     * @return mixed
     */
    public function nextDefinition($sign)
    {
        return $this->wf_definition->getNextDefinition($this->wf_module_id, $this->wf_definition_id, $sign);
    }



    /**
     * @param $level
     * @return mixed
     */
    public function levelDefinition($level)
    {
        return $this->wf_definition->getLevelDefinition($this->wf_module_id, $level);
    }

    /**
     * @param $sign
     * @return mixed
     */
    public function nextWfDefinition($sign)
    {
        return $this->wf_definition->getNextWfDefinition($this->wf_module_id, $this->wf_definition_id, $sign);
    }

    /**
     * @return mixed
     */
    public function currentWfTrack()
    {
        return $this->wf_track->getRecentResourceTrack($this->wf_module_id, $this->resource_id);
    }

    /**
     * @param $sign
     * @return mixed
     */
    public function nextWfTrack()
    {
        $current_track_id = $this->currentTrack();
        return $this->wf_track->getNextWftrackByParentId($current_track_id);
    }



    /**
     * @return mixed
     */
    public function currentTrack()
    {
        $wfTrack = $this->currentWfTrack();
        return $wfTrack->id;
    }

    public function nextWfDefinitionByDefinition($current_wf_definition_id)
    {
        $wfTrack = $this->currentWfTrack();
        return $wfTrack->id;
    }


    /**
     * @param $level
     * @throws GeneralException
     * @description check if the resource is at specified workflow and has already assigned ...
     */
    public function checkLevel($level)
    {
        $wfTrack = $this->currentWfTrack();
        if ($level == $this->currentLevel()) {
            if ($wfTrack->assigned == 1) {
                $return = ['success' => true];
            } else {
                $return = ['success' => false, 'message' => trans('exceptions.backend.workflow.level_not_assigned')];
            }
        } else {
            $return = ['success' => false, 'message' => trans('exceptions.backend.workflow.level_error')];
        }
        if (!$return['success']) {
            throw new GeneralException($return['message']);
        };
    }

    /**
     * @param array ...$levels
     * @throws GeneralException
     * @description Check for Multi level, if the resource is at specified workflow and has already assigned
     */
    public function checkMultiLevel(...$levels)
    {
        $wfTrack = $this->currentWfTrack();
        $return = [];
        foreach ($levels as $level) {
            if ($level == $this->currentLevel()) {
                if ($wfTrack->assigned == 1) {
                    $return = ['success' => true];
                } else {
                    $return = ['success' => false, 'message' => trans('exceptions.backend.workflow.level_not_assigned')];
                }
                break;
            }
        }
        if (empty($return)) {
            $return = ['success' => false, 'message' => trans('exceptions.backend.workflow.level_error')];
        }
        if (!$return['success']) {
            throw new GeneralException($return['message']);
        };
    }

    /**
     * @param $id
     * @param $level
     * @return bool
     * @description check if user has access to a specific level
     */
    public function userHasAccess($id, $level)
    {
        if (env("TESTING_MODE", 0)) {
            $return = true;
        } else {
            $return = $this->wf_definition->userHasAccess($id, $level, $this->wf_module_id);
        }
        return $return;
    }


    /**
     * @param $level
     * @return mixed
     */
    public function previousLevelUser($level)
    {
        return $this->wf_track->previousLevelUser($this->wf_module_id, $level, $this->resource_id);
    }

    /**
     * Write a workflow for the first time
     * @param $input
     * @param int $source, determine whether source is 2 => online or 1 => internally.
     * @throws GeneralException
     * @description Create a new workflow log
     */
    public function createLog($input, $source = 1)
    {
        $current_wf_track = $this->currentWfTrack();
        $insert = [
            'user_id' => $input['user_id'],
            'status' => 1,
            'resource_id' => $input['resource_id'],
            'assigned' => 1,
            'comments' => $input['comments'],
            'wf_definition_id' => $this->wf_definition_id,
            'receive_date' => Carbon::now(),
            'forward_date' => Carbon::now(),
            'station_id' =>$current_wf_track->station_id ?? (isset($input['station_id']) ? $input['station_id'] : station_in_use_id()),
        ];



        /*Check if definition for this resource already created to avoid duplicate*/
        $check_if_already_created = $this->checkIfResourceDefinitionIsAlreadyPending();
        if($check_if_already_created == false) {
            /*If no duplicate entry create new*/
            $track = new WfTrackRepository();
            $wf_track = $track->query()->create($insert);
            switch ($source) {
                case 1:
                    $user = access()->user();
                    $wf_track->user_id = $input['user_id'];
                    $wf_track->assigned = 1;
                    $user->wfTracks()->save($wf_track);
                    break;

            }

            //update Resource Type for the current wftrack
//            $this->updateResourceType($wf_track);
            $nextInsert = $this->upNew($input);

            if(!isset($this->nextWfTrack($wf_track->id)->id)){
                $wf_track = $track->query()->create($nextInsert); // changed
                //update Resource Type for the next wftrack
                $this->updateResourceType($wf_track);

                /*Notify User*/
                $this->notifyNextWfTrack($wf_track);
            }

        }


    }

    /**
     * @param Model $wfTrack
     * @throws GeneralException
     * @description Update the resource type form different resources.
     */
    private function updateResourceType(Model $wfTrack)
    {
        $resourceId = $wfTrack->resource_id;
        //$moduleGroupId = $wfTrack->wfDefinition->wfModule->wfModuleGroup->id;
        switch ($this->wf_module_group_id) {
            case 1:
                //Employee Loan approval
                $loan_approval = (new EmployeeLoanApprovalRepository())->query()->find($resourceId);
                $loan_approval->wfTracks()->save($wfTrack);
                break;

            case 2:
                //Payroll approval
                $payroll_approval = (new PayrollApprovalRepository())->query()->find($resourceId);
                $payroll_approval->wfTracks()->save($wfTrack);
                break;

            case 3:
                //Shift approval
                $shift = (new ShiftRepository())->query()->find($resourceId);
                $shift->wfTracks()->save($wfTrack);
                break;

            case 4:
                //PO approval
                $po = (new PurchaseOrderRepository())->query()->find($resourceId);
                $po->wfTracks()->save($wfTrack);
                break;

            case 5:
                //Product distribution approval
                $distribution = (new ProductMovementRepository())->query()->find($resourceId);
                $distribution->wfTracks()->save($wfTrack);
                break;

            case 6:
                //Employee Allowance approval
                $allowance = (new EmployeeAllowanceRepository())->query()->find($resourceId);
                $allowance->wfTracks()->save($wfTrack);
                break;

            case 7:
                //Employee Deduction approval
                $deduction = (new EmployeeDeductionRepository())->query()->find($resourceId);
                $deduction->wfTracks()->save($wfTrack);
                break;

            case 8:
                //Mid Month Payroll
                $mid_month_payroll = (new MidMonthPayrollRepository())->query()->find($resourceId);
                $mid_month_payroll->wfTracks()->save($wfTrack);
                break;
            case 9:
                //Stock Adjustment
                $stock_adjustment = (new StockAdjustmentRepository())->query()->find($resourceId);
                $stock_adjustment->wfTracks()->save($wfTrack);
                break;
            case 11:
                //staff leave
                $staff_leave = (new StaffLeaveRepository())->query()->find($resourceId);
                $staff_leave->wfTracks()->save($wfTrack);
                break;
            case 12:
                //Employee bulk recovery approval
                $recovery_approval = (new EmployeeBulkRecoveryApprovalRepository())->query()->find($resourceId);
                $recovery_approval->wfTracks()->save($wfTrack);
                break;
            case 13:
                //Sale
                $sale = (new SaleRepository())->query()->find($resourceId);
                $sale->wfTracks()->save($wfTrack);
                break;
        }
    }

    /**
     * @param $input_update
     * @description Update the existing workflow
     */
    public function updateLog($input_update)
    {
        $track = new WfTrackRepository();
        $wf_track = $track->find($this->currentTrack());
        $wf_track->update($input_update);
    }

    /**
     * Assigning a workflow
     * @deprecated since version 1.00
     */
    public function assign()
    {
        $track = new WfTrackRepository();
        $wf_track = $track->find($this->currentTrack());
        $wf_track->user_id = access()->id();
        $wf_track->save();
    }

    /**
     * @param array $input
     * @throws GeneralException
     * @description create the next level information/workflow log.
     */
    public function forward(array $input)
    {
        $wf_track = new WfTrackRepository();
        $nextInsert = $this->upNew($input);
        $check_if_there_is_pending_track = $this->checkIfResourceDefinitionIsAlreadyPendingByModule();
        if($check_if_there_is_pending_track == false){
            $newTrack = $wf_track->query()->create($nextInsert);
            //update Resource Type for the next wftrack
            $this->updateResourceType($newTrack);

            /*Notify User*/
            $this->notifyNextWfTrack($newTrack);
        }
    }

    /**
     * Create the next workflow for the next level to be assigned
     * to the next available user/staff
     * @param $input
     * @return array
     */
    public function upNew($input)
    {
//        $resource = $this->currentWfTrack()->resource;
        $station_id = isset($this->currentWfTrack()->station_id) ? $this->currentWfTrack()->station_id : station_in_use_id();
        $insert = [
            'status' => 0,
            'resource_id' => $input['resource_id'],
            'assigned' => 0,
            'parent_id' => $this->currentTrack(),
            'receive_date' => Carbon::now(),
            'station_id' => isset($input['station_id']) ? $input['station_id'] : $station_id,
        ];


        if ($input['sign'] == -1) {
            $level = $input['level'];
            $insert['wf_definition_id'] = $this->levelDefinition($level);
            $user = $this->previousLevelUser($level);

            if (!is_null($user)) {
                $insert['assigned'] = 1;
                $insert['user_id'] = $user->user_id;
                $insert['allocated'] = $user->user_id;
                $insert['user_type'] = "App\Models\Auth\User";
//                if ($input['source'] == 1) {
//                    $insert['user_type'] = "App\Models\Auth\User";
//                } elseif ($input['source'] == 2) {
////                    $insert['user_type'] = "App\Models\Auth\PortalUser";
//                }

                /*Notify user*/
//                $this->notifyUser($user->user_id, 1);
            }
        } else {
            $level = $this->nextLevel();
            $next_definition = $this->nextWfDefinition($input['sign']);
            $insert['wf_definition_id'] = $this->nextDefinition($input['sign']);
            $group = (new WfModuleGroupRepository())->query()->where("id", $this->wf_module_group_id)->first();
            if ($next_definition->autoaassign == 1 || $next_definition->allow_repeat_participate == 1) {

                if($next_definition->autoassign == 1 ){
                    $user = $this->wfRoundRobin($this->nextDefinition($input['sign']), $station_id);

                }elseif($next_definition->allow_repeat_participate == 1){
                    $parent_definition = WfDefinition::query()->where('id', $next_definition->parent_definition_id)->first();
                    if($parent_definition){
                        $next_user = $this->previousLevelUser($parent_definition->level);
                        $user = isset($next_user) ?  $next_user->user_id : null;
                    }else{
                        $user = null;
                    }

                }

                if (!is_null($user)) {
                    $insert['assigned'] = 1;
                    $insert['user_id'] = $user;
                    $insert['allocated'] = $user;
                    $insert['user_type'] = "App\Models\Auth\User";

                    /*Notify user*/
//                    $this->notifyUser($user, 0);
                }
            }
        }
        //round robin can be implemented Here
        event(new BroadcastWorkflowUpdated($this->wf_module_id, $this->resource_id, $level));
        return $insert;
    }
    /**
     * Check if user has participated in the previous module level.
     * User should not participate twice in the same module.
     * @return bool
     */
    public function hasParticipated()
    {
        $return = $this->wf_track->hasParticipated($this->wf_module_id, $this->resource_id, $this->currentLevel());
        if ($return And env("TEST_MODE")) {
            $return = false;
        }

        return $return;
    }


    public function hasActualAlreadyParticipated()
    {
        $return = $this->wf_track->hasActualAlreadyParticipated($this->wf_module_id, $this->resource_id, $this->currentLevel());
        if ($return And env("TEST_MODE")) {
            $return = false;
        }

        return $return;
    }

    /**
     * @param $input
     * @param $input_update
     * @throws GeneralException
     * @description Workflow Approve Action -- Forward to next level or complete level
     * @deprecated since version 1.00
     */
    public function wfApprove($input,$input_update)
    {
        $this->updateLog($input_update);
        if (!is_null($this->nextLevel())) {
            $this->forward($input);
        }
    }

    /**
     * @description Remove/Deactivate wf_tracks when resource id is cancelled / undone / removed.
     */
    public function wfTracksDeactivate()
    {
        $track = new WfTrackRepository();
        $wf_tracks = $track->query()->where('resource_id', $this->resource_id)->whereHas('wfDefinition', function ($query) {
            $query->where('wf_module_id', $this->wf_module_id);
        })->orderBy('id','desc');
        $wf_tracks->delete();
    }

    /**
     * @return bool
     * @description check if resource has workflow
     */
    public function checkIfHasWorkflow()
    {
        $current_wf_track = $this->currentWfTrack();
        if (isset($current_wf_track)){
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * @return bool
     * @description Check if the workflow resource have had a completed workflow module trip
     */
    public function checkIfExistWorkflowModule()
    {
        $return = false;
        switch ($this->wf_module_group_id) {

            default:
                $return = $this->wf_track->checkIfExistWorkflowModule($this->resource_id, $this->wf_module_id);
                break;
        }
        return $return;
    }

    public function checkIfExistDeclinedWorkflowModule()
    {
        $return = false;
        switch ($this->wf_module_group_id) {

            default:
                $return = $this->wf_track->checkIfExistDeclinedWorkflowModule($this->resource_id, $this->wf_module_id);
                break;
        }
        return $return;
    }

    /**
     * @description Check if is at Level 1 Pending
     * @return bool
     */
    public function checkIfIsLevel1Pending()
    {
        $return = $this->checkIfIsLevelPending(1);
        return $return;
    }

    /**
     * @description Check if is at defined Level Pending
     * @param $level_id
     * @return bool
     */
    public function checkIfIsLevelPending($level_id)
    {
        $current_level  = $this->currentLevel();
        $current_status = $this->currentWfTrack()->status;
        if ($current_level == $level_id && $current_status == 0){
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * @param $level
     * @throws GeneralException
     * @description check if can initiate a level
     */
    public function checkIfCanInitiateAction($level)
    {
        if ($this->checkIfHasWorkflow()) {
            $this->checkLevel($level);
        }
    }

    /**
     * @param $level1
     * @param null $level2
     * @throws GeneralException
     */
    public function checkIfCanInitiateActionMultiLevel($level1, $level2 = null)
    {
        if ($this->checkIfHasWorkflow()) {
            $this->checkMultiLevel($level1, $level2);
        }
    }

    public function hasToAssign()
    {
        $current_level  = $this->currentLevel();
        switch ($current_level) {
            case 3:
                return true;
                break;
            default;
                echo false;
        }
        return false;
    }



    /*Check if current resource definition is pending - when creating new log wf*/
    public function checkIfResourceDefinitionIsAlreadyPending()
    {
        $wf_track =  $this->wf_track->query()->where('resource_id', $this->resource_id)->where('wf_definition_id', $this->wf_definition_id)->where('status',0)->count();
        if($wf_track > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $resource_id
     * @param array $wf_definition_ids
     * @param $status
     * @return bool
     * Check if wt track already exists
     */
    public function checkIfResourceDefinitionIsAlreadyPendingByModule()
    {
        $wf_track =  $this->wf_track->query()->where('resource_id', $this->resource_id)->whereHas('wfDefinition', function($q){
            $q->where('wf_module_id', $this->wf_module_id);
        })->where('status',0)->count();
        if($wf_track > 0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Workflow round Robin
     * @param $definition_id
     * @param $port_id
     * @return null
     */
    public function wfRoundRobin($definition_id, $station_id = null)
    {
        $user_id = null;
        $users = User::query()->whereHas('wfDefinition', function ($query) use ($definition_id){
            $query->where('wf_definitions.id',$definition_id);
        })->orderBy('id', 'asc');

        if(isset($station_id)){
            $users = $users->whereHas('employee', function($q) use ($station_id){
                $q->whereHas('allStations',function($q) use ($station_id) {
                    $q->where('employee_station.station_id', $station_id);
                });
            });
        }
        $wf_definition = new WfDefinitionRepository();
        $definition = $wf_definition->find($definition_id);

        $current_position_pointer = isset($definition->current_position_pointer) ? $definition->current_position_pointer : 0;
        $users_count = $users->count();

        /*round robin starts here*/
        if($users_count > 0) {
            if ($current_position_pointer % $users_count == 0 || $current_position_pointer > $users_count) {
                $current_position_pointer = 0;
            }
            if (isset($users)) {

                /*update pivot current_position_pointer*/
                $definition->update(['current_position_pointer' => $current_position_pointer + 1]);
                /*first user to be assigned*/
                $user = $users->offset($current_position_pointer)->first();
                $user_id = $user->id;
            }
        }
        /*round robin end here*/
        return $user_id;
    }


    /*Notify initiator on the approval*/
    public function notifyUserOnApprovalLastWf()
    {

        $initiator = $this->previousLevelUser(1);
        if($initiator){
            $user_id = $initiator->user_id;
            $this->notifyUser($user_id, 0);
        }
    }


    /*Notify forwarded wf track*/
    public function notifyNextWfTrack($next_wf_track)
    {
        $prev_track = WfTrack::query()->where('id', $next_wf_track->parent_id)->first();
        if($next_wf_track->user_id)
        {
            $isrejected = ($prev_track->status == 2) ? 1 : 0;
            $this->notifyUser($next_wf_track->user_id, $isrejected, 0);
        }
    }
    /**
     * @param $user_id
     * Notify use for sms / email
     */
    public function notifyUser($user_id, $isrejected = 0, $isfinal_wf = 1)
    {
        $current_track = $this->currentWfTrack();

        $resource_name = $current_track->resource->resource_name;
        $resource_name = $resource_name . ' at ' .  organization()->name;
        $comments = $current_track->comments;
        if($isfinal_wf == 1){
            /*final approval*/
            $message = ($isrejected == 0) ? (__('label.notification_narration.workflow.wf_approved', ['resource_name' => $resource_name, 'comments' => $comments])) : (__('label.notification_narration.workflow.wf_rejection', ['resource_name' => $resource_name, 'comments' => $comments]));
            $subject = ($isrejected == 0) ? 'WORKFLOW APPROVED' : 'WORKFLOW REJECTED';
            $subject = $subject . ' - ' . $resource_name;

        }else{
            /*recommendation / rejection*/
            $message = ($isrejected == 0) ? (__('label.notification_narration.workflow.wf_forwarded', ['resource_name' => $resource_name, 'comments' => $comments])) : (__('label.notification_narration.workflow.wf_rejection', ['resource_name' => $resource_name, 'comments' => $comments]));
            $subject = ($isrejected == 0) ? ('WORKFLOW FORWARDED - ' . $resource_name)  : ('WORKFLOW REJECTED - ' . $resource_name);
        }


        $check_if_already_notified_approved = ($isrejected == 0) ? $this->checkIfAlreadyNotifiedOnApproval($current_track) : false;
        if(isset($user_id) && $check_if_already_notified_approved == false){
            $user = User::query()->find($user_id);
            $employee = $user->employee;
            /*Sms*/
            if(sysdef()->data('WFACTSMSTR')){
                /*Send sms*/
                if($employee->phone_1) {
                    $sms_message =  __('label.hi')  .' '  . $employee->firstname . ', ' . $message;
                    SendSms::dispatch($employee, strip_tags($sms_message));
                }

                /*update flag for sms*/
                $current_track->update([
                    'issms' => 1
                ]);
            }

            /*Email*/
            if(sysdef()->data('WFACTEMANT')){
                /*Send email*/
                if($employee->email) {
                    $email_input = [
                        'subject' =>$subject,
                        'message' => $message,
                        'name' => $employee->firstname
                    ];

                    /*employee*/
                    $employee->notify(new GeneralEmailNotifier($email_input));
                }

            }
        }

    }

    /*CHeck if already notified on approval - avoid reinstated resending*/
    public function checkIfAlreadyNotifiedOnApproval($current_wf_track)
    {
        $wf_def = $current_wf_track->wfDefinition;
        $wf_module_id = $wf_def->wf_module_id;
        $final_wf_def = WfDefinition::query()->where('wf_module_id', $wf_module_id)->orderByDesc('level')->first();
        $final_wf_def_id = $final_wf_def->id;
        $check = (new WfTrackRepository())->query()->where('issms', 1)->where('status', 1)->where('wf_definition_id', $final_wf_def_id)->count();
        if($check > 0){
            return true;
        }else{
            return false;
        }
    }

}
