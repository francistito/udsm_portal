<?php


namespace App\Repositories\Access;


use App\Models\Auth\ClientPermission;
use App\Models\Auth\Permission;
use App\Models\Auth\PermissionGroup;
use App\Models\Auth\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ClientPermissionRepository extends BaseRepository
{



    const  MODEL = ClientPermission::class;

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
     * @return \Illuminate\Database\Eloquent\Builder
     * Permission group
     */
    public function getPermissionGroupInClientPermission()
    {
        return PermissionGroup::query()->has('clientPermissions');
    }


    /**
     * @param Model $client_user
     * Permission for admin on registration
     */
    public function syncPermissionForAdmin(Model $client_user)
    {
        $client_user->clientPermissions()->sync([1]);
    }
}