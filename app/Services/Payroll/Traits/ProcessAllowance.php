<?php

namespace App\Services\Payroll\Traits;



use App\Models\Hr\Employee\Allowance\EmployeeAllowance;
use App\Models\Hr\Employee\Employee;
use App\Repositories\Hr\Employee\Allowance\EmployeeAllowanceRepository;
use App\Repositories\Hr\Payroll\PayrollTransactionRepository;
use Illuminate\Support\Facades\Log;

trait ProcessAllowance
{



    /*Find allowance Taxable*/
    public function findTotalAllowance($employee_id, $istaxable, $days_attended = null)
    {
        $total_allowance = 0;// non recurring/specific
        $total_allowance_recurring = 0;
        /*Non Recurring*/
        $active_allowances = $this->queryAllActiveAllowance($employee_id, $istaxable)->get();

        foreach($active_allowances as $allowance)
        {
            $allowance_amount = $allowance->amount_payable_per_cycle;
            $total_allowance = $total_allowance + $allowance_amount;
        }

        /*Recurring allowances*/
        $recurring_allowance_types = $this->queryRecurringAllowanceTypesForEmployee($employee_id, $istaxable)->get();
        foreach ($recurring_allowance_types as $allowance_type){
            if($allowance_type->is_attendance == 0){
                /*Normal*/
                $allowance_amount_recur = $allowance_type->amount;
            }else{
                $allowance_amount_recur = $allowance_type->amount * $days_attended;
            }

            $total_allowance_recurring = $total_allowance_recurring + $allowance_amount_recur;
        }

        return $total_allowance + $total_allowance_recurring;
    }

    /**
     * @param $employee_id
     * @param $istaxable
     * @return \Illuminate\Database\Eloquent\Builder
     * Employee id
     * Istaxable is flag to check if taxable
     * Get all active allowance for this payroll
     */
    public function queryAllActiveAllowance($employee_id, $istaxable = null)
    {
        if(isset($istaxable)){
            $allowances_query = $this->queryAllEligibleAllowance($employee_id)->whereHas('allowanceType', function($query) use($istaxable){
                $query->where('istaxable', $istaxable);
            });
        }else{
            $allowances_query = $this->queryAllEligibleAllowance($employee_id);
        }


        return $allowances_query;
    }

    /*Query recurring allowance types by employee*/
    public function queryRecurringAllowanceTypesForEmployee($employee_id, $istaxable=null)
    {
        $employee = Employee::query()->find($employee_id);
        if(isset($istaxable)){
            $allowance_types = $employee->allowanceTypes()->where('employee_allowance_types.isrecurring', 1)->where('employee_allowance_types.isactive', 1)->where('employee_allowance_types.istaxable', $istaxable);
        }else{
            $allowance_types = $employee->allowanceTypes()->where('employee_allowance_types.isrecurring', 1)->where('employee_allowance_types.isactive', 1);
        }
        return $allowance_types;
    }

    /*Get all eligible allowance query*/
    public function queryAllEligibleAllowance($employee_id)
    {
        $allowances_query = EmployeeAllowance::query()->where('employee_id', $employee_id)->where('isactive', 1);
        return $allowances_query;
    }

    /**
     * @param $employee_id
     * Save all active allowance into payroll transaction
     */
    public function saveAllowancesToPayrollTransactions($employee_id,$payroll_approval_id)
    {
        /*non recurring/specified to only user*/
        $employee = Employee::query()->find($employee_id);
        $active_allowances = $this->queryAllActiveAllowance($employee_id)->get();
        foreach($active_allowances as $allowance)
        {
            $allowance_amount = $allowance->amount;
            $input = [
                'payroll_approval_id' =>$payroll_approval_id,
                'amount' => $allowance_amount,
                'resource_id' => $allowance->id,
                'remark' => $allowance->allowanceType->name  . ' ('. number_2_format($allowance->total_allowance_amount) . ' of ' . short_date_format($allowance->wf_done_date) . ')',
                'employee_id' => $employee_id,
                'iscredit' => 1,
            ];
            (new PayrollTransactionRepository())->store( $allowance, $input);
        }

        /*Save recurring allowances*/
        $recurring_allowance_types = $this->queryRecurringAllowanceTypesForEmployee($employee_id)->get();
        foreach ($recurring_allowance_types as $allowance_type){
            /*If is attendance -> get attendance days*/
            if($allowance_type->is_attendance == 1){
                $basic_salary_attendance = $this->findBasicSalaryByAttendance($employee, $this->payroll_approval_id);
              $days_attended = $basic_salary_attendance['days_attended'];
            }

            $input = [
                'payroll_approval_id' => $this->payroll_approval_id,
                'amount' => $allowance_type->amount * ($days_attended ?? 1),
                'resource_id' => $allowance_type->id,
                'remark' => $allowance_type->name ,
                'employee_id' => $employee_id,
                'isrecurring' => 1,
                'iscredit' => 1,

            ];
            (new PayrollTransactionRepository())->store( $allowance_type, $input);
        }

    }


    /*Update allowance table after workflow approval*/
    public function updateAllowancesAfterApproval($employee_id)
    {
        $active_allowances = $this->queryAllActiveAllowance($employee_id)->whereHas('payrollTransactions', function($q){
            $q->where('payroll_transactions.payroll_approval_id', $this->payroll_approval_id)->where('payroll_transactions.ispaid', 0);
        })->get();
        foreach($active_allowances as $allowance){
            if($allowance->allowanceType->isrecurring == 0){
                /*non recurring allowance*/
                (new EmployeeAllowanceRepository())->updateAfterWorkflowApproval($allowance, $this->payroll_approval_id);
            }else{
                /*recurring allowance*/
                (new EmployeeAllowanceRepository())->updateAfterPayrollApprovalRecurring($allowance);

            }
        }
    }



}
