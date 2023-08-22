<?php

namespace App\Services\Payroll\Traits;



use App\Models\Hr\Employee\Allowance\EmployeeAllowance;
use App\Models\Hr\Employee\Employee;
use App\Models\Hr\Payroll\PayrollApproval;
use App\Repositories\Hr\Payroll\PayrollApprovalRepository;
use App\Repositories\Operation\Attendance\AttendanceRepository;
use App\Repositories\Operation\Employee\Allowance\EmployeeAllowanceRepository;
use App\Repositories\Operation\Payroll\PayrollTransactionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ProcessBasicSalaryAttendanceTrait
{


    /**
     * @param Model $employee
     * @param $payroll_approval
     */
    public function findBasicSalaryByAttendance(Model $employee, $payroll_approval_id = null)
    {
        $payroll_approval_repo = new PayrollApprovalRepository();

        $staff_payroll_category_cv_id = $employee->staff_payroll_category_cv_id;
        $daily_rate = $employee->basic_salary;
        if($payroll_approval_id){
            /*On Payroll*/
            $payroll_approval = PayrollApproval::query()->find($payroll_approval_id);
            $from_date = $payroll_approval->from_date ?? $payroll_approval->payroll_month;
            $to_date = $payroll_approval->payroll_month;
        }else{
            /*On review*/
            $main_station_id = $employee->main_station->id;
            $last_approval = $payroll_approval_repo->findLatestApprovalByPayrollCategory($staff_payroll_category_cv_id,$main_station_id);
            if($last_approval){
                $payroll_month = $last_approval->payroll_month;
                $from_date = $last_approval->from_date;
            }else{
                $payroll_month = $payroll_approval_repo->findCurrentMonthForPayrollByCategory($staff_payroll_category_cv_id,$main_station_id);
                $from_date =standard_date_format(Carbon::parse($payroll_month)->subDays(sysdef()->data('HRPYATTDAY')));
            }

            $to_date = $payroll_month;
        }


        $days_attended = (new AttendanceRepository())->geAttendanceByResourceOnDateRange($employee, $from_date, $to_date);
        $basic_salary = $days_attended * $daily_rate;

        return ['basic_salary' => $basic_salary, 'days_attended' => $days_attended];
    }




}