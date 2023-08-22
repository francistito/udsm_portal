<?php

namespace App\Services\Payroll\Traits;



use App\Models\Hr\Employee\Loan\EmployeeLoanRepayment;
use App\Repositories\Hr\Employee\Loan\EmployeeLoanRepaymentRepository;
use App\Repositories\Hr\Payroll\PayrollTransactionRepository;
use Illuminate\Support\Facades\Log;

trait ProcessLoan
{


    /*Find Loan Taxable*/
    public function findTotalLoan($employee_id)
    {
        $total_loan = 0;
        $active_Loans = $this->queryAllActiveLoan($employee_id)->get();
        foreach($active_Loans as $loan)
        {
            $loan_amount = $loan->amount_payable_per_cycle;
            $total_loan = $total_loan + $loan_amount;
        }

        return $total_loan;
    }

    /**
     * @param $employee_id
     * @param $istaxable
     * @return \Illuminate\Database\Eloquent\Builder
     * Employee id
     * Istaxable is flag to check if taxable
     * Get all active Loan for this payroll
     */
    public function queryAllActiveLoan($employee_id)
    {
        $loans_query = $this->queryAllEligibleLoan($employee_id);

        return $loans_query;
    }

    /*Get all eligible Loan query*/
    public function queryAllEligibleLoan($employee_id)
    {
        $loans_query = EmployeeLoanRepayment::query()->whereHas('loanApproval', function ($query) use($employee_id){
            $query->where('employee_loan_approvals.employee_id', $employee_id)->where('employee_loan_approvals.isactive', 1);
        })->where('isactive', 1)->where('recycles', '>', 0);
        return $loans_query;
    }

    /**
     * @param $employee_id
     * Save all active Loan into payroll transaction
     * @param $payroll_approval_id
     */
    public function saveLoansToPayrollTransactions($employee_id,$payroll_approval_id)
    {
        $active_loans = $this->queryAllActiveLoan($employee_id)->get();
        foreach($active_loans as $loan)
        {
            $loan_amount = $loan->amount_payable_per_cycle;
            $input = [
                'payroll_approval_id' => $payroll_approval_id,
                'amount' => $loan_amount,
                'resource_id' => $loan->id,
                'remark' => $loan->loanApproval->loanType->name  . '('. number_2_format($loan->loanApproval->amount) . ' of ' . short_date_format($loan->loanApproval->wf_done_date) . ')',
                'employee_id' => $loan->loanApproval->employee_id,
                'iscredit' => 0,
                'isrecurring' => 0,//not infinite recurring
            ];
            (new PayrollTransactionRepository())->store( $loan, $input);

        }
    }


    /*Update Loan table after workflow approval*/
    public function updateLoansAfterApproval($employee_id)
    {
        $active_loans = $this->queryAllActiveLoan($employee_id)->whereHas('payrollTransactions', function($q){
            $q->where('payroll_transactions.payroll_approval_id', $this->payroll_approval_id)->where('payroll_transactions.ispaid', 0);
        })->get();
        foreach($active_loans as $loan){
            (new EmployeeLoanRepaymentRepository())->updateAfterWorkflowApproval($loan, $this->payroll_approval_id);
        }
    }





}
