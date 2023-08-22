<?php

namespace App\Services\Payroll;



use App\Exceptions\GeneralException;
use App\Http\Controllers\Report\Operation\LoanCreditorReportController;
use App\Models\Hr\Employee\Allowance\EmployeeAllowance;
use App\Models\Hr\Employee\Deduction\EmployeeDeduction;
use App\Models\Hr\Employee\Employee;
use App\Models\Hr\Employee\Loan\EmployeeLoanRepayment;
use App\Models\Hr\Payroll\PayrollApproval;
use App\Models\Hr\Payroll\PayrollCompliance;
use App\Models\Hr\Payroll\PayrollTransaction;
use App\Repositories\Accounting\AccountRepository;
use App\Repositories\Accounting\JournalEntryRepository;
use App\Repositories\Hr\Payroll\EmployeeSalaryRepository;
use App\Repositories\Hr\Payroll\HealthInsuranceRangeRepository;
use App\Repositories\Hr\Payroll\PayeRangeRepository;
use App\Repositories\Hr\Payroll\PayrollApprovalRepository;

use App\Repositories\Hr\Employee\Allowance\EmployeeAllowanceRepository;
use App\Repositories\Hr\Employee\Deduction\EmployeeDeductionRepository;
use App\Repositories\Hr\Employee\EmployeeRepository;
use App\Repositories\Hr\Employee\Loan\EmployeeLoanRepaymentRepository;

use App\Repositories\Operation\Attendance\AttendanceRepository;

use App\Repositories\Operation\Expense\ExpenseRepository;
use App\Repositories\Hr\Payroll\PayrollRunRepository;
use App\Repositories\Hr\Payroll\PayrollTransactionRepository;
use App\Services\Payroll\Traits\ProcessAllowance;
use App\Services\Payroll\Traits\ProcessBasicSalaryAttendanceTrait;
use App\Services\Payroll\Traits\ProcessDeduction;
use App\Services\Payroll\Traits\ProcessJournalEntriesPayroll;
use App\Services\Payroll\Traits\ProcessLoan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessMonthlyPayroll
{

    use ProcessDeduction, ProcessAllowance, ProcessLoan, ProcessJournalEntriesPayroll, ProcessBasicSalaryAttendanceTrait;

    protected $payroll_approval_id;

    protected $employee_exception;


    public function __construct($payroll_approval_id = null)
    {
        $this->payroll_approval_id = $payroll_approval_id;

    }


    /**
     * Process payroll
     * @param $payroll_approval
     */
    public function processPayroll($payroll_approval)
    {
        DB::transaction(function() use($payroll_approval) {
            $payroll_approval_repo = new PayrollApprovalRepository();
//            $payroll_approval = $payroll_approval_repo->find($this->payroll_approval_id);
            $employees = $this->getActiveEmployees($payroll_approval);
            foreach ($employees as $employee) {
                $this->processPayrollForEmployee($employee->id,$payroll_approval);
                /*check and update run status of payroll*/
//                $payroll_approval_repo->updateRunStatus($this->payroll_approval_id);

                /*Check employee exception*/
//                $this->checkEmployeeException($payroll_approval, $employee->id);
            }

            /*Update totals*/
            $this->updateTotalsAfterRunCompleted($payroll_approval->id);
        });
    }


    /*Process monthly salary for individual/employee*/
    public function processPayrollForEmployee($employee_id,$payroll_approval)
    {
        $payroll_approval_id = $payroll_approval->id;
        DB::transaction(function() use($employee_id,$payroll_approval_id) {
            $input = $this->getPayrollRunData($employee_id);
            $input['payroll_approval_id'] = $payroll_approval_id;
            (new EmployeeSalaryRepository())->store($employee_id,$input);

//            $previous_payroll_approval = (new PayrollApprovalRepository())->findPreviousApproval($this->payroll_approval_id);
//            $previous_run = ($previous_payroll_approval) ? (new PayrollRunRepository())->findEmployeeRunByApprovalId($input['employee_id'], $previous_payroll_approval->id) : null;
//            $input['isbasicsalary_changed'] = ($previous_run) ? (($input['basic_salary'] != $previous_run->basic_salary) ? 1 : 0) : 0;
//            $input['isemployeefundcontrib_changed'] = ($previous_run) ? (($input['employee_fund_contrib'] != $previous_run->employee_fund_contrib) ? 1 : 0) : 0;
            /*basic salary changed*/
            (new PayrollRunRepository())->store($input);

            /*Save payroll transactions*/
            $this->savePayrollTransactions($employee_id,$payroll_approval_id);
//            if($input['net_amount'] > 0){
//                /*Save payroll run*/
//
//            }


        });
    }

    /*Save Payroll*/
    public function getPayrollRunData($employee_id)
    {

        $input = [];
        $employee = Employee::query()->find($employee_id);
        $employment_term_cv_ref = isset($employee->employment_term_cv_id) ? code_value()->reference($employee->employment_term_cv_id) : 'EMPTRMPRI';
        $payroll_category_cv_ref = $employee->staffPayrollCategory->reference ?? 'SPCMONTH';
        $days_attended = null;
        switch($payroll_category_cv_ref){
            case 'SPCATTEND':
                $basic_salary_attendance = $this->findBasicSalaryByAttendance($employee, $this->payroll_approval_id);
                $input['basic_salary']  = $basic_salary_attendance['basic_salary'];
                $input['days_attended']  = $basic_salary_attendance['days_attended'];
                $days_attended =      $input['days_attended'] ;
                break;
            default:
                $input['basic_salary']   = $employee->basic_salary ?? 0;
                break;
        }

        $input['iscontracted']  = ($employee->employmentType->reference == 'EMTYCON') ? 1 : 0;
        $iscontracted = $input['iscontracted'];
        /*Start deduction*/
        $input['deduction_taxable'] = $this->findTotalDeduction($employee_id, 1);
        $input['deduction_non_taxable']  = $this->findTotalDeduction($employee_id, 0);
        /*end deduction*/

        /*Start allowance*/
        $input['allowance_taxable']  = $this->findTotalAllowance($employee_id, 1, $days_attended);
        $input['allowance_non_taxable']  = $this->findTotalAllowance($employee_id, 0,$days_attended);

        /*end allowance*/

        /*Start gross salary*/
        $gross_salary  = $input['basic_salary']  +  $input['allowance_taxable'];
        $gross_salary = $gross_salary -    $input['deduction_taxable'];
        $input['gross_salary']   =$gross_salary;
        /*End gross salary*/


        /*start sdl*/
        $input['sdl_amount']  =($iscontracted == 1) ? $this->findPayrollCompliance(2, $gross_salary, $employee) : 0;
        /*end sdl*/

        /*start wcf*/
        $input['wcf_amount']  = ($iscontracted == 1) ? $this->findPayrollCompliance(1, $gross_salary, $employee ) : 0;
        /*end wcf*/

        /*Start social fund*/
        $input['employee_fund_contrib']  = ($iscontracted == 1 && $employment_term_cv_ref == 'EMPTRMPRI') ? $this->findPayrollCompliance(3,   $gross_salary, $employee ) : 0;
        $input['employer_fund_contrib']  = ($iscontracted == 1 && $employment_term_cv_ref == 'EMPTRMPRI') ? $this->findPayrollCompliance(4,   $gross_salary, $employee ) : 0;
        /*end social fund*/

        /*Start Health Insurance*/
        $basic_salary_use = ($gross_salary <  $input['basic_salary'] ) ? $gross_salary : $input['basic_salary'];
        $health_distr = (new HealthInsuranceRangeRepository())->findInsuranceDistribution($basic_salary_use );
        $input['health_employee_contrib']  = ($iscontracted == 1) ? $health_distr['employee_contrib'] : 0;
        $input['health_employer_contrib']  = ($iscontracted == 1) ?  $health_distr['employer_contrib']: 0;
        /*end health insurance*/


        /*start paye*/
        $input['taxable_income'] = $gross_salary - $input['employee_fund_contrib'] ;
        $input['paye_amount'] = (($iscontracted == 1 && sysdef()->data('PAYCOEMPE') == false) || ($iscontracted == 1 && sysdef()->data('PAYCOEMPE') && $employee->is_paye)) ? (new PayeRangeRepository())->findPaye( $input['taxable_income'], $employment_term_cv_ref ) : 0;
        /*end paye*/


        /*Start loan*/
        $input['loan_amount'] = $this->findTotalLoan($employee_id);
        /*end loan*/

        /*Net Payable*/
        $input['net_amount'] = $input['taxable_income'] + $input['allowance_non_taxable'] - $input['deduction_non_taxable'] - $input['loan_amount']  - $input['paye_amount']  - $input['health_employee_contrib'];

        /*General info*/
        $input['bank_id']  = $employee->bank_id;
        $input['accountno']  = $employee->accountno;

        $input['payroll_approval_id']  = $this->payroll_approval_id;
        $input['employee_id']  = $employee_id;
        $input['organization_id']  = $employee->organization_id;
        $input['station_id']  =isset($employee->main_station) ?  $employee->main_station->id : (($employee->allStations()->count() > 0) ? $employee->allStations()->first()->id : null);

        $input['employment_term_cv_id']  = $employee->employment_term_cv_id;
        $input['employment_category_cv_id']  = $employee->employment_category_cv_id;

        return $input;
    }



    /*Save all payroll transactions by employee i.e. deduction, allowance, payroll loan*/
    public function savePayrollTransactions($employee_id,$payroll_approval_id)
    {
        $this->saveDeductionsToPayrollTransactions($employee_id,$payroll_approval_id);
        $this->saveAllowancesToPayrollTransactions($employee_id,$payroll_approval_id);
        $this->saveLoansToPayrollTransactions($employee_id,$payroll_approval_id);
    }


    /*Get Active Employees for payroll*/
    public function getActiveEmployees($payroll_approval)
    {
        $staff_payroll_category_cv_id = $payroll_approval->staff_payroll_category_cv_id;

        $staff_payroll_category_cv_id = isset($staff_payroll_category_cv_id) ? $staff_payroll_category_cv_id : code_value()->getIdByReference('SPCMONTH');
        $staff_payroll_category_cv_ref = code_value()->find($staff_payroll_category_cv_id)->reference;
        /*Get employees based on the payroll category*/
        switch($staff_payroll_category_cv_ref){
            case 'SPCATTEND'://By Attendances
                $query = (new EmployeeRepository())->queryEmployeesForPayroll($payroll_approval->station_id)->where('employees.staff_payroll_category_cv_id',$staff_payroll_category_cv_id)->where('ev.station_site_id',$payroll_approval->station_site_id);

                $employees = (new AttendanceRepository())->getQueryEligibleResourcesAttendedOnDateRange($payroll_approval->from_date, $payroll_approval->payroll_month, $query, 'employees.id', 'App\Models\Hr\Employee\Employee')->get();

                break;

            default:


                $employees = (new EmployeeRepository())->queryEmployeesForPayroll($payroll_approval->station_id)->where('employees.staff_payroll_category_cv_id',$staff_payroll_category_cv_id)->get();

                break;
        }
//        $employees = (new EmployeeRepository())->queryEmployeesForPayroll($payroll_approval->station_id)->where('employees.staff_payroll_category_cv_id',$staff_payroll_category_cv_id)->get();

        return $employees;
    }
    /*Get Active Employees for payroll in this payroll once payroll has started*/
    public function getActiveEmployeesInThisPayroll()
    {
        $employees = (new EmployeeRepository())->query()->whereHas('payrollRuns', function($q){
            $q->where('payroll_approval_id', $this->payroll_approval_id);
        })->get();
        return $employees;
    }


    /*Find Payroll compliance*/
    public function findPayrollCompliance($payroll_compliance_id, $gross_salary, $employee = null)
    {
        $payroll_compliance = PayrollCompliance::query()->find($payroll_compliance_id);
        $value = $payroll_compliance->value;
        $return = 0;
        if(sysdef()->data('PAYCOEMPE') == false){
            /*Normal approach*/
            switch ($payroll_compliance_id)
            {
                case 1:
                    /*wcf*/

                case 2:
                    /*sdl*/
                case 3:
                    /*employee social fund %*/

                case 4:
                    /*employer social fund*/
                    $return = $gross_salary * (0.01 * $value);
                    break;

            }
        }else{
            /*with employee payroll compliance*/
            switch ($payroll_compliance_id)
            {
                case 1:
                    /*wcf*/
                    $return = ($employee->is_wcf) ? ($gross_salary * (0.01 * $value)) : 0;
                    break;
                case 2:
                    /*sdl*/
                    $return = ($employee->is_sdl) ? ($gross_salary * (0.01 * $value)) : 0;
                    break;
                case 3:
                    /*employee social fund %*/
                    $value = ($employee->is_nssf) ? ($employee->employee_social_fund_percent_formatted ?? 0) : 0;
                    $return = ($gross_salary * (0.01 * $value));
                    break;
                case 4:
                    /*employer social fund*/
                    $value = ($employee->is_nssf) ? ($employee->employer_social_fund_percent_formatted ?? 0) : 0;
                    $return = ($gross_salary * (0.01 * $value));

                    break;
            }
        }

        return $return;
    }


    /*Update all amounts onto payroll approval after run is completed*/
    public function updateTotalsAfterRunCompleted($payroll_approval_id)
    {
        $input = [];
        $approval = (new PayrollApprovalRepository())->find($payroll_approval_id);
        $approval_id = $payroll_approval_id;
        $input['no_of_employees'] = (new PayrollApprovalRepository())->queryEmployeesForPayrollByCategory($approval->staff_payroll_category_cv_id, $approval->station_id, $approval->from_date, $approval->payroll_month,  $approval->station_site_id)->count();
        $input['total_net_amount'] = $this->queryPayrollRunsByApproval($approval_id)->sum('net_amount');
        $input['total_deduction_taxable'] = $this->queryPayrollRunsByApproval($approval_id)->sum('deduction_taxable');
        $input['total_deduction_non_taxable'] = $this->queryPayrollRunsByApproval($approval_id)->sum('deduction_non_taxable');
        $input['total_allowance_taxable'] = $this->queryPayrollRunsByApproval($approval_id)->sum('allowance_taxable');
        $input['total_allowance_non_taxable'] = $this->queryPayrollRunsByApproval($approval_id)->sum('allowance_non_taxable');
        $input['total_employee_fund_contrib'] = $this->queryPayrollRunsByApproval($approval_id)->sum('employee_fund_contrib');
        $input['total_employer_fund_contrib'] = $this->queryPayrollRunsByApproval($approval_id)->sum('employer_fund_contrib');
        $input['total_sdl'] = $this->queryPayrollRunsByApproval($approval_id)->sum('sdl_amount');
        $input['total_wcf'] = $this->queryPayrollRunsByApproval($approval_id)->sum('wcf_amount');
        $input['total_paye_amount'] = $this->queryPayrollRunsByApproval($approval_id)->sum('paye_amount');
        $input['total_loan_amount'] = $this->queryPayrollRunsByApproval($approval_id)->sum('loan_amount');
        $input['total_basic_salary'] = $this->queryPayrollRunsByApproval($approval_id)->sum('basic_salary');
        $input['total_health_employee_contrib'] = $this->queryPayrollRunsByApproval($approval_id)->sum('health_employee_contrib');
        $input['total_health_employer_contrib'] = $this->queryPayrollRunsByApproval($approval_id)->sum('health_employer_contrib');
        $input['stage_error'] = $approval->stage_error ?? $this->employee_exception;
        /*Update totals*/
        (new PayrollApprovalRepository())->updateTotals($approval_id, $input);
    }

    public function queryPayrollRunsByApproval($payroll_approval_id)
    {
        return (new PayrollRunRepository())->query()->where('payroll_approval_id', $payroll_approval_id);
    }





    /*Update payroll transactions pay flag after workflow complete*/
    public function updatePayrollTransactionPayFlag($payroll_approval_id)
    {
        PayrollTransaction::query()->where('payroll_approval_id', $payroll_approval_id)->update([
            'ispaid' => 1
        ]);
    }

    /*Update payroll transactions pay flag after workflow complete*/
    public function updatePayrollTransactionPayFlagByEmployee($payroll_approval_id, $employee_id)
    {
        PayrollTransaction::query()->where('payroll_approval_id', $payroll_approval_id)->where('employee_id', $employee_id)->update([
            'ispaid' => 1
        ]);
    }

    /*Update after workflow complete - Old approach*/
    public function updateAfterWfCompleteOld()
    {
        try {
            $employees = $this->getActiveEmployeesInThisPayroll();
            foreach($employees as $employee){
                DB::transaction(function() use($employee) {
                    $employee_id = $employee->id;
                    /*Deduction*/
                    $this->updateDeductionsAfterApproval($employee_id);
                    /*Allowances*/
                    $this->updateAllowancesAfterApproval($employee_id);

                    /*Loan*/
                    $this->updateLoansAfterApproval($employee_id);

                    /*Payroll transactions paid flag*/
                    $this->updatePayrollTransactionPayFlagByEmployee($this->payroll_approval_id, $employee_id);
                });
            }

        } catch (\Exception $e) {
            Log::info(print_r($e,true));
            report($e);

        }

    }

    /*Update after workflow complete - NEW*/
    public function updateAfterWfComplete($user_id = null)
    {

        try {
            $payroll_approval = (new PayrollApprovalRepository())->find($this->payroll_approval_id);
            DB::transaction(function () use($user_id, $payroll_approval){
                /*update recoveries from transactions*/
                $trans_date = (comparable_date_format($payroll_approval->payroll_month) <= comparable_date_format(getTodayDate())) ? standard_date_format($payroll_approval->payroll_month) : standard_date_format(getTodayDate());

                $this->updatePayrollTransactionsAndRecoveriesAfterWfApproval();

                /*Process Journal*/
                if(sysdef()->data('FAAVACCONT') && sysdef()->data('PAYLWAGBL')) {
                    $this->processPayrollJournals($this->payroll_approval_id, $user_id);

                    /*Post payroll bill expense - on settings need to be enabled*/
                    if(sysdef()->data('PAYLWAGBL')){
                        (new ExpenseRepository())->postMainPayrollBills($payroll_approval, ['user_id' => $user_id, 'trans_date' => $trans_date]);
                    }
                }



                /*other validation*/
                $this->otherPayrollValidation($payroll_approval);

            });

        } catch (\Exception $e) {
//            Log::info(print_r($e,true));
            report($e);
            $this->postStageError($e);

        }

    }
    /*Update payroll transaction and recoveries after wf approval*/
    public function updatePayrollTransactionsAndRecoveriesAfterWfApproval()
    {

        /*RECURRING TRANS--*/
        $non_recurring_payroll_trans = (new PayrollTransactionRepository())->queryTransactionByPayrollByPaidStatus($this->payroll_approval_id, 0)->where('isrecurring', 0)->get();
        $payroll_approval_id = $this->payroll_approval_id;
        foreach ($non_recurring_payroll_trans as $payroll_tran) {


            switch ($payroll_tran->resource_type) {
                case 'App\Models\Hr\Employee\Allowance\EmployeeAllowance':

                    $allowance_repo = (new EmployeeAllowanceRepository());
                    $allowance = $allowance_repo->find($payroll_tran->resource_id);
                    /*Allowance*/
                    $allowance_repo->updateAfterWorkflowApproval($allowance, $payroll_approval_id);

                    break;

                case 'App\Models\Hr\Employee\Deduction\EmployeeDeduction':
                    $deduction_repo = (new EmployeeDeductionRepository());
                    $deduction = $deduction_repo->find($payroll_tran->resource_id);
                    /*deduction*/
                    $deduction_repo->updateAfterWorkflowApproval($deduction, $payroll_approval_id);


                    break;


                case 'App\Models\Hr\Employee\Loan\EmployeeLoanRepayment':
                    $loan_repayment_repo = (new EmployeeLoanRepaymentRepository());
                    $loan_repay = $loan_repayment_repo->find($payroll_tran->resource_id);
                    /*loan repayment*/
                    $loan_repayment_repo->updateAfterWorkflowApproval($loan_repay, $payroll_approval_id);
                    /*update paid flag*/

                    break;
            }
            /*Update tran flag*/
            (new PayrollTransactionRepository())->updatePaidFlag($payroll_tran, 1);


        }


        /*UPDATE RECURRING - TRANSACTIONS--*/
        (new PayrollTransactionRepository())->queryTransactionByPayrollByPaidStatus($this->payroll_approval_id, 0)->where('isrecurring', 1)->update([
            'ispaid' => 1
        ]);


    }



    /*Check if payment processed success fully after payroll approved*/
    public function checkIfPaymentProcessedSuccessfully()
    {
        $error_message = '';
        $payroll_approval_id = $this->payroll_approval_id;
        $payroll_approval= (new PayrollApprovalRepository())->find($payroll_approval_id);


        $check_allowance = EmployeeAllowance::query()->whereHas('payrollTransactions', function($q) use($payroll_approval_id) {
            $q->where('payroll_approval_id',$payroll_approval_id);
        })->whereRaw('coalesce(last_payroll_approval_id,0) <> ?',[$payroll_approval_id])->count();

        $check_deduction = EmployeeDeduction::query()->whereHas('payrollTransactions', function($q)use($payroll_approval_id){
            $q->where('payroll_approval_id',$payroll_approval_id);
        })->whereRaw('coalesce(last_payroll_approval_id,0) <> ?',[$payroll_approval_id])->count();


        $check_loan_repayments = EmployeeLoanRepayment::query()->whereHas('payrollTransactions', function($q) use($payroll_approval_id){
            $q->where('payroll_approval_id',$payroll_approval_id);
        })->whereRaw('coalesce(last_payroll_approval_id,0) <> ?',[$payroll_approval_id])->count();

        /*check if there is journal*/
        $check_journal_entries = $payroll_approval->journalEntries()->count();

        /*check if there is error*/
        $check_error = $payroll_approval->stage_error;


        /*Check if has expense*/
        $check_bill_expense = $payroll_approval->expense()->count();

        if($check_allowance > 0 || $check_deduction > 0 || $check_loan_repayments > 0){
            $error_message = 'Recoveries not updated last payroll approval (with issues) : Allowance -' . $check_allowance . ', Deductions -' . $check_deduction . ', Loan Repayment - '. $check_loan_repayments;
//            Log::info(print_r($error_message,true));
            log_info($error_message);
            return false;
        }elseif($check_error != null){
            $error_message = 'There is error issued while processing payment for this payroll! Please check';
            log_info($error_message);
            return false;
        }elseif($check_journal_entries == 0 && sysdef()->data('FAAVACCONT') && sysdef()->data('PAYLWAGBL')){
            $error_message = 'There is error issued posting journal entries! Please check!';
            log_info($error_message);
            return false;
        }elseif($check_bill_expense == 0 && sysdef()->data('FAAVACCONT') && sysdef()->data('PAYLWAGBL')){
            $error_message = 'There is error, No bill expense generated! Please check!';
            log_info($error_message);
            return false;
        }else{
            return true;
        }
    }


    public function postStageError($error_message)
    {
        $payroll_approval = (new PayrollApprovalRepository())->find($this->payroll_approval_id);
        $account_error = (sysdef()->data('FAAVACCONT')) ? 'Accounting: Make sure payroll accounting settings are properly set; allowance type, deduction types, loan types and payroll settings.' : '';
        $payroll_approval->update([
            'stage_error' => truncateString(($account_error.  ' ' . 'Tech Explanation:' . $error_message), 1500)
        ]);
    }


    /*Other validations*/
    public function otherPayrollValidation(Model $payrollApproval){
        $valid = true;
        $message = '';
        $check_if_journal_balance =(sysdef()->data('FAAVACCONT') && sysdef()->data('PAYLWAGBL')) ? (new JournalEntryRepository())->checkIfTransResourceJournalBalance($payrollApproval) : true;
        if($check_if_journal_balance == false){
            $valid = false;
            $message = 'Journal Entries for this payroll not balanced! Please check!';
            $this->postStageError($message);
        }

        if($valid == false){
            throw new GeneralException($message);
        }
    }


    /**
     * @param Model $payroll_approval
     * @param Model $employeec
     * check employee exception
     */
    public function checkEmployeeException(Model $payroll_approval, $employee_id)
    {
        $employee = Employee::query()->find($employee_id);

        $staff_payroll_category_cv_ref = $payroll_approval->staffPayrollCategory->reference;
$exception = null;
        switch ($staff_payroll_category_cv_ref) {
            case 'SPCATTEND':
                /*Check if attendance -> check if dates overlap*/
                $from_date = $payroll_approval->from_date ?? $payroll_approval->payroll_month;
                $to_date = $payroll_approval->payroll_month;
                $check_overlap = DB::table('payroll_runs_view')->where('employee_id', $employee_id)->whereRaw(" ((from_date < ? and payroll_month > ?) or (from_date < ? and payroll_month > ?)) ", [($from_date), $from_date, $to_date, $to_date])->first();

                if($check_overlap){
                    $exception= $exception  . ',  Overlap dates this payroll : ' . $check_overlap->payroll_month;
                }
                break;
        }

        if($exception){
            $this->employee_exception = $this->employee_exception . ', ' . $employee->name . ' : ' . $exception;
        }

    }
}
