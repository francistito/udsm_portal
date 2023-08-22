<?php

namespace App\Services\Payroll\Traits;



use App\Models\Hr\Employee\Allowance\EmployeeAllowanceType;
use App\Models\Hr\Employee\Deduction\EmployeeDeductionType;
use App\Models\Hr\Employee\Loan\EmployeeLoanRepayment;
use App\Models\Hr\Employee\Loan\EmployeeLoanType;
use App\Models\Hr\Payroll\PayrollTransaction;
use App\Models\Operation\Station\Station;
use App\Repositories\Accounting\JournalEntryRepository;
use App\Repositories\Accounting\JournalTransTypeRepository;
use App\Repositories\Hr\Payroll\PayrollAccountingRepository;
use App\Repositories\Hr\Payroll\PayrollApprovalRepository;
use App\Repositories\Hr\Employee\Loan\EmployeeLoanRepaymentRepository;
use App\Repositories\Hr\Payroll\PayrollRunRepository;
use App\Repositories\Hr\Payroll\PayrollTransactionRepository;
use App\Repositories\Operation\Station\StationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ProcessJournalEntriesPayroll
{


    public function processPayrollJournals($payroll_approval_id, $user_id)
    {
        DB::transaction(function() use($payroll_approval_id,$user_id) {
            $payroll_approval = (new PayrollApprovalRepository())->find($payroll_approval_id);
            $this->reverseJournalEntriesPayroll($payroll_approval);
            $this->postJournalForPayroll($payroll_approval, $user_id);

        });
    }


    /*Post Journal For General Expense*/
    public function postJournalForPayroll(Model $payroll_approval, $user_id, $action_type =1)
    {

        $trans_type = (new JournalTransTypeRepository())->findByReference('HRPAYSALWG');
        $trans_date = (comparable_date_format($payroll_approval->payroll_month) <= comparable_date_format(getTodayDate())) ? standard_date_format($payroll_approval->payroll_month) : standard_date_format(getTodayDate());
        $journal_data = [
            'parent_entry_id' => null,
            'trans_date' => $trans_date,
            'trans_resource_id' => $payroll_approval->id,
            'trans_resource_type' => 'App\Models\Hr\Payroll\PayrollApproval',
//                    'entity_resource_id' =>$po_repayment->supplier_id,
//                    'entity_resource_type' =>'App\Models\Operation\Supplier\Supplier',
            'journal_trans_type_id' => $trans_type->id,
            'product_id' => null,
            'station_id' => null,
            'shift_id' => null,
            'description' => $payroll_approval->resource_name,
            'user_id' => $user_id,
            'action_type' => $action_type
        ];

        /*Allowance*/
        $this->processAllowanceJournal($payroll_approval, $journal_data);
//        /*deduction*/
        $this->processDeductionJournal($payroll_approval, $journal_data);
        /*Loan*/
        $this->processLoanJournal($payroll_approval, $journal_data);
        /*compliance + net pay*/
        $this->processComplianceJournal($payroll_approval, $journal_data);

    }
    /*Process Allowance*/
    public function processAllowanceJournal(Model $payroll_approval, $journal_data)
    {
        $journal_entry_repo = new JournalEntryRepository();
        $pay_trans_repo = new PayrollTransactionRepository();
        $payroll_approval_id = $payroll_approval->id;
        $allowance_types = EmployeeAllowanceType::query()->get();
        $description = $journal_data['description'];
        $stations = $this->getStationsInThisPayroll($payroll_approval_id);

        foreach($allowance_types as $allowance_type){
            $allowance_type_id = $allowance_type->id;

            /*Loop for each station for this allowance type*/
            foreach ($stations as $station){
                $station_id  = $station->id;

                $journal_data['station_id'] = $station_id;
                $total_amount = $pay_trans_repo->getAllowanceForPayrollApprovalTypeGroupByStation($payroll_approval_id, $allowance_type_id,$station_id);
                $expense_account_id = $allowance_type->expense_account_id;
                $journal_data['description'] =  $description. ': ' . $allowance_type->name . ' ('. $station->name . ')';
                $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$total_amount, 1, $journal_data, null);

            }


        }
    }

    /*Process Deduction*/
    public function processDeductionJournal(Model $payroll_approval, $journal_data)
    {
        $journal_entry_repo = new JournalEntryRepository();
        $pay_trans_repo = new PayrollTransactionRepository();
        $payroll_approval_id = $payroll_approval->id;
        $deduction_types = EmployeeDeductionType::query()->get();
        $description = $journal_data['description'];
        $stations = $this->getStationsInThisPayroll($payroll_approval_id);
        foreach($deduction_types as $deduction_type){
            $deduction_type_id = $deduction_type->id;
            /*Loop for each station for this deduction type*/
            foreach ($stations as $station){
                $station_id  = $station->id;
                $journal_data['station_id'] = $station_id;
                $total_amount = $pay_trans_repo->getDeductionForPayrollApprovalTypeGroupByStation($payroll_approval_id, $deduction_type_id,$station_id);
                /*Accounts*/
//                if($deduction_type->istaxable) {
//                    /*Taxable*/
//                    $account_id = $deduction_type->expense_account_id;
//                }else {
//                    /*non taxable*/
//                    $account_id = $deduction_type->receivable_account_id;
//                }
                $account_id = ($deduction_type->ismidmonth_payroll == 0) ? $deduction_type->expense_account_id :  $deduction_type->receivable_account_id;
                $journal_data['description'] = $description . ': ' . $deduction_type->name .  ' ('. $station->name . ')';
                $entry =  $journal_entry_repo->singleJournalPostingGeneral($account_id,$total_amount, 0, $journal_data, null);
            }
        }
    }

    /*Process Loans*/
    public function processLoanJournal(Model $payroll_approval, $journal_data)
    {
        $journal_entry_repo = new JournalEntryRepository();
        $pay_trans_repo = new PayrollTransactionRepository();
        $payroll_approval_id = $payroll_approval->id;
        $loan_types = EmployeeLoanType::query()->get();
        $description = $journal_data['description'];
        $stations = $this->getStationsInThisPayroll($payroll_approval_id);
        foreach ($loan_types as $loan_type) {
            $loan_type_id = $loan_type->id;
            /*Loop for each station for this loan type*/
            foreach ($stations as $station){
                $station_id  = $station->id;
                $journal_data['station_id'] = $station_id;
                $total_amount = $pay_trans_repo->getLoansForPayrollApprovalTypeGroupByStation($payroll_approval_id, $loan_type_id,$station_id);
                /*Accounts*/
                $receivable_account_id = $loan_type->receivable_account_id;

                $journal_data['description'] =$description. ': ' . $loan_type->name  .  ' ('. $station->name . ')';
                $entry = $journal_entry_repo->singleJournalPostingGeneral($receivable_account_id, $total_amount, 0, $journal_data, null);
            }
        }
    }



    /*Process Compliance expenses*/
    public function processComplianceJournal(Model $payroll_approval, $journal_data)
    {

        $journal_entry_repo = new JournalEntryRepository;
        $payroll_accounting_repo = new PayrollAccountingRepository();
        $payroll_run_repo = new PayrollRunRepository();
        $station_repo = new StationRepository();
        $description =  $journal_data['description'] ;

        $payroll_runs_group_by_station = $payroll_run_repo->getTotalAmountsGroupByStationForPayrollApproval($payroll_approval->id);
        foreach ($payroll_runs_group_by_station as $payroll_by_station_total){
            $station =  isset($payroll_by_station_total->station_id) ? $station_repo->find($payroll_by_station_total->station_id) : '';
            $station_id = $station->id ?? null;
            $station_name = $station->name ?? null;
            $journal_data['station_id'] = $station_id;
             /*PAYE*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PATRAPAYE');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $paye_amount = $payroll_by_station_total->paye_amount;
            $journal_data['description'] = $description. ': ' . $accounting_setting->name    .  ' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$paye_amount, 1, $journal_data, null);
            $parent_entry_id =  ($expense_entry) ?  $expense_entry->id : null;
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$paye_amount, 1, $journal_data, $parent_entry_id);
            /*end -- paye*/

            /*SDL*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PATRASDL');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $sdl_amount = $payroll_by_station_total->sdl_amount;
            $journal_data['description'] = $description . ': ' . $accounting_setting->name   .  ' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$sdl_amount, 1, $journal_data, null);
            $parent_entry_id =  ($expense_entry) ?  $expense_entry->id : null;
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$sdl_amount, 1, $journal_data, $parent_entry_id);
            /*end -- sdl*/

            /*wcf*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PAWCF');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $wcf_amount = $payroll_by_station_total->wcf_amount;
            $journal_data['description'] = $description . ': ' . $accounting_setting->name .' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$wcf_amount, 1, $journal_data, null);
            $parent_entry_id =   ($expense_entry) ?  $expense_entry->id : null;
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$wcf_amount, 1, $journal_data, $parent_entry_id);
            /*end -- wcf*/


            /*Social Security fund SSF*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PASSF');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $ssf_amount = $payroll_by_station_total->employee_fund_contrib + $payroll_by_station_total->employer_fund_contrib;
            $journal_data['description'] = $description . ': ' . $accounting_setting->name  .  ' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$ssf_amount, 1, $journal_data, null);
            $parent_entry_id =  ($expense_entry) ?  $expense_entry->id : null;
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$ssf_amount, 1, $journal_data, $parent_entry_id);
            /*end -- ssf*/


            /*Health Care/Insurance*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PAHEALTH');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $health_amount = $payroll_by_station_total->health_employee_contrib + $payroll_by_station_total->health_employer_contrib;
            $journal_data['description'] = $description . ': ' . $accounting_setting->name  .  ' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$health_amount, 1, $journal_data, null);
            $parent_entry_id = ($expense_entry) ?  $expense_entry->id : null;
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$health_amount, 1, $journal_data, $parent_entry_id);
            /*end -- health*/

            /*Process Net pay*/
            $accounting_setting = $payroll_accounting_repo->findByReference('PANETSAL');
            $expense_account_id = $accounting_setting->expense_account_id;
            $payable_account_id = $accounting_setting->payable_account_id;
            $net_salary_payable = $payroll_by_station_total->net_amount;
            $total_allowance = $payroll_by_station_total->allowance_taxable + $payroll_by_station_total->allowance_non_taxable;
            $total_deduction = $payroll_by_station_total->deduction_taxable + $payroll_by_station_total->deduction_non_taxable;
            $total_loan = $payroll_by_station_total->loan_amount;
            $net_salary_expense = $net_salary_payable - ($total_allowance - ($total_deduction + $total_loan));
            $journal_data['description'] = $description . ': ' . $accounting_setting->name  .  ' ('. $station_name . ')';
            $expense_entry =  $journal_entry_repo->singleJournalPostingGeneral($expense_account_id,$net_salary_expense, 1, $journal_data, null);
            $payable_entry =  $journal_entry_repo->singleJournalPostingGeneral($payable_account_id,$net_salary_payable, 1, $journal_data, null);
        }
    }








    /*Reverse Journal Entries*/
    public function reverseJournalEntriesPayroll(Model $payroll_approval)
    {
        $payroll_approval->journalEntries()->forceDelete();

    }




    /**
     * Get Stations which are in this payroll
     */

    public function getStationsInThisPayroll($payroll_approval_id)
    {
        $stations = Station::query()->whereHas('payrollRuns', function($q) use($payroll_approval_id){
            $q->where('payroll_approval_id',$payroll_approval_id);
        })->get();

        return $stations;
    }

}