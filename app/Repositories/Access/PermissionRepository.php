<?php


namespace App\Repositories\Access;


use App\Models\Auth\Permission;
use App\Models\Auth\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Seeder;

class PermissionRepository extends BaseRepository
{



    const  MODEL = Permission::class;

    /*Get all permissions*/
    public  function  getAll()
    {
        return $this->query()->orderBy('display_name')->get();

    }

    /*Get all permissions which are non administrative*/
    public function getAllNonAdministrative()
    {
        return $this->query()->where('isadmin', 0)->orderBy('display_name')->get();
    }


    /*Check if permission is in user roles*/
    public function checkIfPermissionIsInUserRoles($user_id, $permission_id)
    {
        $user = User::query()->find($user_id);
        $check = $user->roles()->whereHas('permissions', function($query) use($permission_id){
            $query->where('permissions.id', $permission_id);
        })->count();
        if($check > 0)
        {
            return true;
        }else{
            return false;
        }
    }


    /**
     * Temporary rectify <02-Mar-2022> todo remove after april 22 <rectify_function>
     */
    public function rectifyPermissionForExisting()
    {


        (new \Version100TableSeeder())->runRectifierSeeder();
        /*Recurring sale*/
        $roles = (new RoleRepository())->query()->whereHas('permissions', function ($q) {
            $q->whereIn('permissions.id', [135, 137]);
        })->get();

        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([238]);
        }

        /*Recurring expense*/
        $roles = (new RoleRepository())->query()->whereHas('permissions', function ($q) {
            $q->whereIn('permissions.id', [121, 125]);
        })->get();

        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([239]);
        }


        /*View Sale total amount*/
        $roles = (new RoleRepository())->query()->whereHas('permissions', function ($q) {
            $q->whereIn('permissions.id', [146]);
        })->get();


        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([240, 241]);
        }


        /*View*/
    }

        public function rectifyPermissionForExistingProductServices()
    {


        (new \Version100TableSeeder())->runRectifierSeeder();
        /*Product & service details on expense*/
        $roles = (new RoleRepository())->query()->whereHas('permissions', function ($q) {
            $q->whereIn('permissions.id', [46]);
        })->get();


        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([245]);
        }


        /*Update sell price point of sale*/
        $roles = (new RoleRepository())->query()->whereHas('permissions', function ($q) {
            $q->whereIn('permissions.id', [135]);
        })->get();


        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching([248]);
        }

    }

}