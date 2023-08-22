<?php


namespace App\Services\Notifications;


use App\Models\Hr\Employee\Employee;
use App\Models\System\EmailBatch;
use App\Notifications\Salary\SalarySlipNotification;
use App\Repositories\Hr\Employee\EmployeeRepository;
use App\Repositories\Hr\Payroll\EmployeeSalaryRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EmployeeForSendSalarySlip extends Notification implements ShouldQueue
{
    public function __construct()
    {


    }

//    public function setNews($news){
//    }

}
