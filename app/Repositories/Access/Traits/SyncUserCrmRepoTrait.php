<?php


namespace App\Repositories\Access\Traits;


use App\Exceptions\GeneralException;
use App\Models\Auth\User;
use App\Models\Operation\Expense\Expense;
use App\Models\Operation\Expense\ExpensePayment;
use App\Models\Operation\Expense\ExpenseType;
use App\Models\Operation\Station\Station;
use App\Models\System\Banking\PaymentMethod;
use App\Models\System\Banking\PaymentTerm;
use App\Repositories\Access\UserRepository;
use App\Repositories\Accounting\AccountRepository;
use App\Repositories\Accounting\JournalEntryRepository;
use App\Repositories\Accounting\JournalTransTypeRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Operation\Sales\ShiftRepository;
use App\Repositories\Operation\Station\StationRepository;
use App\Repositories\Operation\Supplier\SupplierRepository;
use App\Repositories\Operation\Transaction\DetailTransactionRepository;
use App\Repositories\System\DocumentResourceRepository;
use App\Services\Api\GeneralCrmApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait SyncUserCrmRepoTrait
{


    /**
     * @param array $input
     * @return mixed
     * Onboard customer into client server
     */
    public function syncUsersIntoCrmApi(Model $user)
    {
        return DB::transaction(function () use ($user) {
            $employee = $user->employee;
            $last_login_user = (new UserRepository())->getLastLoginUser();
            $input['remote_resource_id'] = $user->id;
            $input['email'] = $user->email;
            $input['phone_number'] = $user->phone;
            $input['phone'] = $user->phone;
            $input['country_id'] = country()->id;
            $input['country_code'] = country()->code;
            $input['username'] = $user->username;
//            $input['password'] = $user->password;//todo need to commecnt
            $input['firstname'] = $employee->firstname ?? $user->username;
            $input['lastname'] = $employee->lastname ?? $user->username;
            $input['saas_app_ref'] = 'NEXTACCNT';
            $input['last_login'] = ($last_login_user) ? $last_login_user->last_login : null;

            $input['customer_uuid'] = organization()->uuid;

            $nextcrm_app_url = (env('TEST_MODE',1) == 0) ? config('env.nextcrm.app_url')  : config('env.nextcrm.dev_app_url') ;

            $full_url = $nextcrm_app_url . DIRECTORY_SEPARATOR. 'customer/user/sync_user_from_remote_saas';

            $api = new GeneralCrmApi($input);
            $response =  $api->sendJsonPost($full_url);

            $data = json_decode($response);

            if (isset($data)) {
                if ($data->message == "SUCCESS") {
                    //file posted successful
                    $user_update_info_arr = json_decode($data->user_update_info_json, true);
                    $this->saveDataFromCrmOnSyncUser($user,$user_update_info_arr);
                    return true;
                }
            } else {
                throw new GeneralException("Failed to sync user into client server");
            }
        });
    }

    /**
     * @param Model $user
     * @param array $input
     */
    public function saveDataFromCrmOnSyncUser(Model $user, array $input)
    {
        $user->update([
            'main_customers_data' => $input['main_customers_data']
        ]);
    }
    /**
     * @param Model $user
     * @param array $input
     * @return boolLogin fields has changed
     */
    public function checkIfUsernameFieldsHasChanged(Model $user, array $input)
    {

        if($user->email == ($input['email'] ?? $user->email)  && $user->phone == ($input['phone'] ?? $user->phone)  && $user->username == $input['username']){
            /*Has not changed*/
            return false;
        }else{
            return true;
        }
    }


    /**
     * @param array $input
     * @return mixed
     * Confirmation code through crm
     */
    public function notifyConfirmationCodeThroughCrm(Model $user)
    {
        return DB::transaction(function () use ($user) {
            $employee = $user->employee;
            $input['remote_resource_id'] = $user->id;
            $input['email'] = $user->email;
            $input['phone_number'] = $user->phone;
            $input['phone'] = $user->phone;
            $input['saas_app_ref'] = 'NEXTACCNT';

            $input['customer_uuid'] = organization()->uuid;

            $nextcrm_app_url = (env('TEST_MODE',1) == 0) ? config('env.nextcrm.app_url')  : config('env.nextcrm.dev_app_url') ;

            $full_url = $nextcrm_app_url . DIRECTORY_SEPARATOR. 'customer/user/notify_confirmation_code_api';

            $api = new GeneralCrmApi($input);
            $response =  $api->sendJsonPost($full_url);

            $data = json_decode($response);

            if (isset($data)) {
                if ($data->message == "SUCCESS") {
                    //file posted successful
                    return true;
                }
            } else {
                throw new GeneralException("Failed to notify confirmation code through crm");
            }
        });
    }


}
