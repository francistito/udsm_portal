<?php

namespace App\Repositories\Access;

use App\Exceptions\GeneralException;
use App\Jobs\Notifications\SendSms;
use App\Models\Auth\ClientUser;
use App\Models\Auth\ClientUserLog;
use App\Models\Auth\UserLog;
use App\Models\Operation\Client\Client;
use App\Models\Operation\Sales\Sale;
use App\Notifications\GeneralEmailNotifier;
use App\Repositories\BaseRepository;
use App\Repositories\System\UnitRepository;
use App\Repositories\Workflow\WfTrackRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClientUserRepository extends BaseRepository
{

    const MODEL = ClientUser::class;


    /**
     * @param array $input
     * Confirm code client portal
     */
    public function getConfirmationCode(array $input)
    {
        try {
            $phone = $input['phone'];
            $phone = phone_make($phone, country_code());
            $email = strtolower($input['email']);
            $client = Client::query()->whereRaw(" (phone = ? and email = ? )", [$phone, $email])->first();
            $message_warning = null;
            if($client){
                $client_user_check = $client->clientUsers()->count();

                if($client_user_check == 0){
                    $confirmation_code = get_rand_letter_number(4) . get_otp(2) . $client->id;
                    $message = '<p>Your confirmation code for your portal account is  <b> '  .$confirmation_code .' </b> </p>';
                    $message = $message . '<p>Regards, <br/>'. organization()->name . '</p> ';
                    $subject = 'Portal Account: Confirmation Code at ' . organization()->name ;
                    $input = ['message' => $message,'subject' => $subject, 'name' => $client->name, 'to_email' => $client->email];

                    /*Update*/
                    $client->update([
                        'portal_confirmation_code' => $confirmation_code
                    ]);

                    /*Send email*/
                    if(isset($client->email) &&  filter_var($client->email, FILTER_VALIDATE_EMAIL)){
                        $client->notify(new GeneralEmailNotifier($input));
                    }


                    /*Send sms*/
                    if($client->phone) {
                        $sms_message = __('label.hi')  .' '  . $client->name . ', ' . $message;
                        SendSms::dispatch($client, strip_tags($sms_message));
                    }


                }else{
                    $message_warning = 'Already Registered on portal! Please check!';
                }
            }else{
                $message_warning = 'Client Not Found on our records! Please check details filled if accurate?';
            }

            $success = ($message_warning) ? false : true;
            return [
                'message' => $message_warning,
                'success' => $success
            ];

        } catch (\Exception $e) {
            report($e);
            return [
                'message' => __('exceptions.general.try_exception'),
                'success' => false
            ];

        }
    }


    /**
     * @param array $input
     * Create client user
     */
    public function create(array $input)
    {
        return DB::transaction(function () use ($input ) {
            $client = Client::query()->where('portal_confirmation_code', $input['confirmation_code'])->first();
            $client_user =  $this->query()->create([
                'firstname' => $input['firstname'],
                'middlename' => $input['middlename'],
                'lastname' => $input['lastname'],
                'username' => $input['email'],
                'password' => bcrypt($input['password']),
                'email' => $input['email'],
                'phone' => phone_make($input['phone'], country_code()),
                'confirmed' => true,
                'confirmation_code' => $input['confirmation_code'],
                'confirmed_at' => Carbon::now(),
                'isactive' => 1,
                'client_id' => $client->id
            ]);


            /*Attach permission for admin client user*/
            if($client->clientUsers()->count() == 1){
                (new ClientPermissionRepository())->syncPermissionForAdmin($client_user);
            }

            return $client_user;

        });
    }


    /**
     * @param array $input
     * @return mixed
     * Change Password of contact person /portal user.
     */
    public function changePassword($client_user, array $input)
    {


        $client_user->update(['password' => bcrypt($input['password'])]);

        return $client_user;
    }



    /* Update user information */
    public function update($user, array $input)
    {

        /*end check phone*/
        return DB::transaction(function () use ($input,$user ) {
            /*Check phone*/
            $this->checkIfPhoneIsUnique(phone_make($input['phone'], country_code()), 'phone', 2, $user->id );
            $user->update([
                'username' =>strtolower($input['username']),
                'phone' => phone_make($input['phone'], country_code()),
                'email' =>strtolower($input['email']),
                'firstname' => $input['firstname'],
                'middlename' => $input['middlename'],
                'lastname' => $input['lastname'],
//                'phone' => $phone_formatted,
//                'email' => $input['email'],
            ]);

            /*Permission*/
            if(isset($input['ispermission'])){
                $this->syncClientPermission($user, $input);
            }

            return $user;
        });

    }


    /**
     * @param Model $client_user
     * @param array $input
     * Sync client permission
     */
    public function syncClientPermission(Model $client_user, array $input)
    {
        return  DB :: transaction(function() use ($input, $client_user){
            $permissions = [];
            foreach ($input as $key => $value) {
                if (strpos($key, 'client_permission') !== false) {
                    $client_permission_id = substr($key, 17);
                    array_push($permissions, $client_permission_id);
                }
            };
            $client_user->clientPermissions()->sync($permissions);

            return $client_user;
        });
    }

    /**
     * @param array $input
     * Reset password
     */
    public function resetPasswordEmail(array $input)
    {
        DB::transaction(function () use ($input ) {
            $client_user = $this->query()->where('email', strtolower($input['email']))->first();

            if($client_user){

                $new_password = get_rand_letter_number(4) . get_otp(2) . $client_user->id;


                $subject = 'Reset Password at ' . organization()->name ;
                $message = '<p>Your reset password is <b> ' . $new_password. ' </b>. Make sure you change to your preferred password after login  </p>';
                $message = $message . '<p>Regards, <br/>'. organization()->name . '</p> ';
                $input = ['message' => $message,'subject' => $subject, 'name' => $client_user->firstname, 'to_email' => $client_user->email];
                if(isset($client_user->email) &&  filter_var($client_user->email, FILTER_VALIDATE_EMAIL)){

                    $client_user->update([
                        'password' => bcrypt($new_password)
                    ]);

                    $client_user->notify(new GeneralEmailNotifier($input));
                }
            }else{
                throw new GeneralException('Account not found on our records! Please check!');
            }
        });
    }



    public function getClientUsersByClientDt($client_id)
    {
        return $this->query()->where('client_id', $client_id);
    }


    /**
     * @param $client_user_id
     * @return mixed
     * Pending workflow
     */
    public function getPendingWorkflowForClientForDt($client_id)
    {

        $client = Client::query()->find($client_id);
        $client_user_ids = $client->clientUsers()->pluck('client_users.id');

        $wf_tracks = (new WfTrackRepository())->query()->whereNotNull('user_id')->whereIn('user_id', $client_user_ids)->where('user_type', 'App\Models\Auth\ClientUser')->where('status', 0);

//        $client_user
        /*Sales*/
        $external_unit_id = (new UnitRepository())->findByReferenceBase('EXTRNAL')->id ?? null;
        $pending_sales_ids = Sale::query()->where('payer_resource_id', $client->id)->where('payer_resource_type','App\Models\Operation\Client\Client')->where('wf_done', 0)->pluck('id');
        $sale_wf_tracks = (new WfTrackRepository())->query()->where('status', 0)->whereHas('wfDefinition', function($q) use($external_unit_id){
            $q->where('wf_definitions.unit_id', $external_unit_id);
        })->whereIn('resource_id',$pending_sales_ids)->where('resource_type', 'App\Models\Operation\Sales\Sale');

        return $wf_tracks->union($sale_wf_tracks);

    }
}