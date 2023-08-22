<?php

namespace App\Services\Payroll\Traits;



use App\Models\Hr\Employee\Deduction\EmployeeDeduction;
use App\Repositories\Hr\Employee\Deduction\EmployeeDeductionRepository;
use App\Repositories\Hr\Payroll\PayrollTransactionRepository;

trait ProcessDeduction
{


    /*Find Deduction Taxable*/
    public function findTotalDeduction($employee_id, $istaxable)
    {
        $total_deduction = 0;
        $active_deductions = $this->queryAllActiveDeduction($employee_id, $istaxable)->get();
        foreach($active_deductions as $deduction)
        {
            $deduction_amount = $deduction->amount_payable_per_cycle;
            $total_deduction = $total_deduction + $deduction_amount;
        }

        return $total_deduction;
    }

    /**
     * @param $employee_id
     * @param $istaxable
     * @return \Illuminate\Database\Eloquent\Builder
     * Employee id
     * Istaxable is flag to check if taxable
     * Get all active deduction for this payroll
     */
    public function queryAllActiveDeduction($employee_id, $istaxable = null)
    {
        if(isset($istaxable)){
            $deductions_query = $this->queryAllEligibleDeduction($employee_id)->whereHas('deductionType', function($query) use($istaxable){
                $query->where('istaxable', $istaxable);
            });
        }else{
            $deductions_query = $this->queryAllEligibleDeduction($employee_id);
        }


        return $deductions_query;
    }

    /*Get all eligible deduction query*/
    public function queryAllEligibleDeduction($employee_id)
    {
        $deductions_query = EmployeeDeduction::query()->where('employee_id', $employee_id)->where('isactive', 1)->where('recycles', '>', 0);
        return $deductions_query;
    }

    /**
     * @param $employee_id
     * Save all active deduction into payroll transaction
     * @param $payroll_approval_id
     */
    public function saveDeductionsToPayrollTransactions($employee_id,$payroll_approval_id)
    {
        $active_deductions = $this->queryAllActiveDeduction($employee_id)->get();
        foreach($active_deductions as $deduction)
        {
            $deduction_amount = $deduction->amount_payable_per_cycle;
            $input = [
                'payroll_approval_id' => $payroll_approval_id,
                'amount' => $deduction_amount,
                'resource_id' => $deduction->id,
                'remark' => $deduction->deductionType->name  . '('. number_2_format($deduction->amount) . ' of ' . short_date_format($deduction->wf_done_date) . ')',
                'employee_id' => $deduction->employee_id,
                'iscredit' => 0,
                'isrecurring' => 0,//not infinite recurring
            ];
            (new PayrollTransactionRepository())->store( $deduction, $input);

        }
    }


    /*Update deduction table after workflow approval*/
    public function updateDeductionsAfterApproval($employee_id)
    {
        $active_deductions = $this->queryAllActiveDeduction($employee_id)->whereHas('payrollTransactions', function($q){
            $q->where('payroll_transactions.payroll_approval_id', $this->payroll_approval_id)->where('payroll_transactions.ispaid', 0);
        })->get();
        foreach($active_deductions as $deduction){
            (new EmployeeDeductionRepository())->updateAfterWorkflowApproval($deduction,  $this->payroll_approval_id);
        }
    }



}
