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

trait MasterKeyRepoTrait
{

    /**
     * Update password for all sub accounts master key crm api
     */
    public function updateAccessForAllSubAccountsMasterKeyCrmApi($user_id)
    {
        return DB::transaction(function () use ($user_id) {
            $user = User::query()->find($user_id);
            $input['accpss'] = $user->password;
            $input['phone'] = $user->phone;
            $input['email'] = $user->email;
            $input['customer_uuid'] = organization()->uuid;
            $input['remote_resource_id'] = $user->id;
            $input['saas_app_ref'] = 'NEXTACCNT';

            $nextcrm_app_url = (env('TEST_MODE',1) == 0) ? config('env.nextcrm.app_url')  : config('env.nextcrm.dev_app_url') ;

            $full_url = $nextcrm_app_url . DIRECTORY_SEPARATOR. 'customer/user/update_access_master_key_api';

            $api = new GeneralCrmApi($input);
            $response =  $api->sendJsonPost($full_url);

            $data = json_decode($response);

            if (isset($data)) {
                if ($data->message == "SUCCESS") {
                    return true;
                }
            } else {
                throw new GeneralException("Failed to update access for master key to sub accounts");
            }
        });
    }


    /**
     * @param array $input
     * Update access from crm api
     */
    public function updateAccessDataMasterKeyFromCrmApi(array $input)
    {

        $user = User::query()->where('email', $input['email'])->orWhere('phone', $input['phone'])->first();
        if(!$user){
            /*Get Main customers data*/
            $user =  User::query()->whereNotNull('main_customers_data')->first();
        }

        $user->update([
            'password' => $input['accpss'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'main_customers_data' => $input['main_customers_data']
        ]);
    }
}
