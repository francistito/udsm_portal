<?php

namespace App\Repositories\Access;

use App\Models\Access\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UserRepository extends BaseRepository
{

    const MODEL = User::class;

    /**
     * @param array $input
     * @param $employee
     * @return mixed
     * Register company, both clearing and forwarding agents and normal company
     */
    public function store(array $input)
    {
        $user = User::where('email',$input['email'])->where('phone_number',$input['phone_number'])->first();
        if ($user)
        {
            return $user;
        }else{
            $user = DB::transaction(function () use ($input) {
                $user = $this->query()->create([
                    'firstname' => ($input['firstname']),
                    'lastname' => ($input['lastname']),
                    'phone_number' => $input['phone_number'],
                    'email' => $input['email'],
                    'confirmed' => true,
                    'password' =>bcrypt( $input['password']),
                    'confirmed_at' => Carbon::now(),
                ]);
                return $user;
            });
        }

        return $user;

    }

    /*Send registration notification*/
    public function sendRegistrationNotification(Model $user)
    {

        //Send Confirmation Code Email

        $user->employee->notify(new UserNeedsConfirmation());

        /* Send Welcome SMS */
        SendSms::dispatch($user, trans("strings.sms.registered")  . ' ' .  $user->confirmation_code);
    }

    public function sendPasswordNotification(Model $user,$password,$username)
    {
        if($user->employee){
            $user->employee->notify(new SendPasswordNotification($password,$username));
            /* Send Welcome SMS */
            SendSms::dispatch($user->employee, trans("strings.sms.registered")  . ' ' .  $password);
        }

    }


    /* Update user information */
    public function update($user, array $input)
    {

        /*end check phone*/
        return DB::transaction(function () use ($input,$user ) {
            /*Check phone*/
            $this->checkIfPhoneIsUnique(phone_make($input['phone'], country_code()), 'phone', 2, $user->id );
            $check_if_login_field_changed = $this->checkIfUsernameFieldsHasChanged($user, $input);
            $user->update([
                'username' =>strtolower($input['username']),
                'phone' => phone_make($input['phone'], country_code()),
                'email' =>strtolower($input['email']),
//                'firstname' => $input['firstname'],
//                'middlename' => $input['middlename'],
//                'lastname' => $input['lastname'],
//                'phone' => $phone_formatted,
//                'email' => $input['email'],
            ]);

            /*API: Sync user into crm*/
            if($check_if_login_field_changed){

                $this->syncUsersIntoCrmApi($user);
            }

            /*Update Master key update*/
            /*Dispatch job*/
            if($user->main_customers_data){
                dispatch(new UpdateAccessDataMasterKeyCrmApiJob($user->id));
            }
            return $user;
        });

    }


    /**
     * @param array $input
     * @return mixed
     * Change Password of contact person /portal user.
     */
    public function changePassword($user, array $input)
    {

        /*Update Master key update*/
        /*Dispatch job*/
        if($user->main_customers_data){

            dispatch(new UpdateAccessDataMasterKeyCrmApiJob($user->id));
        }

        $user->update(['password' => bcrypt($input['password'])]);

        return $user;
    }



    /**
     * @param $token
     * @return mixed
     */
    public function findByConfirmationToken($token)
    {
        return $this->query()->where('confirmation_code', $token)->first();
    }

    /**
     * @param $token
     * @return mixed
     * @throws GeneralException
     */
    public function confirmAccount($token)
    {
        $user = $this->findByConfirmationToken($token);

        if ($user->confirmed == '1') {
            throw new GeneralException(__('exceptions.auth.confirmation.already_confirmed'));
        }

        if ($user->confirmation_code == $token) {
            $user->confirmed = '1';
            $user->save();
            return access()->login($user);
        }
        throw new GeneralException(trans('exceptions.auth.confirmation.mismatch'));
    }

    public function getName($id){
        $user = $this->query()->select('name')->where('id',$id)->first()->name;
        return $user;
    }





    public function attachRoles(array $input, Model $user){
        $role_array = [];
        foreach ($input as $key => $value) {
            switch ($key)  {
                case 'roles':
                    $role_array = $value;
                    break;
            }
        }
        $user->roles()->sync($role_array);

    }


    /**
     * @param Model $user
     * Attach permissions based on roles attached to the user
     */
    public function attachRolePermissions(Model $user)
    {
        $array = [];
        $permissions = [];
        $roles = $user->roles;
        foreach($roles as $role){
            $array = $role->permissions()->pluck("permissions.id")->all();
//            $array = $permissions;
            $permissions = array_merge($array, $permissions);
        }

        $user->permissions()->sync($permissions);

    }




    /**
     * @return int
     * Generate 6 digits
     */
    public function randomConfirmationCode()
    {
        return mt_rand(100000,999999);
    }



    public function destroy(User $user)
    {
        DB::table('user_logs')->where('user_id', $user->id);
        $user->delete();
        return true;
    }


    /**
     * @return mixed
     * Last login
     */
    public function getLastLoginUser()
    {
        return $this->query()->whereNotNull('last_login')->orderByDesc('last_login')->first();
    }


    /**
     * @param $user_id
     * Update personalized flags
     */
    public function updatePersonalizedFlags($user_id, $flag_index, $value)
    {
        $user = $this->find($user_id);
        $personalized_flags = $user->personalized_flags;
        if($personalized_flags)
        {
            $flags_arr = json_decode($personalized_flags, true);


            $flags_arr[$flag_index] = $value;



        }else{
            $flags_arr = [
                $flag_index => $value
            ];
        }


        $user->update([
            'personalized_flags' => json_encode($flags_arr)
        ]);
    }


    /**
     * Rectify personalized flag
     */
    public function rectifyPersonalizedFlagPosImage()
    {
        $users = User::query()->get();
        foreach($users as $user){
            if($user->personalized_flags) {
                $flags = json_decode($user->personalized_flags, true);
                if (!array_key_exists('pos_image', $flags)) {
                    $this->updatePersonalizedFlags($user->id, 'pos_image', true);
                }
            }else{
                $this->updatePersonalizedFlags($user->id, 'pos_image', true);
            }

        }
    }


}
