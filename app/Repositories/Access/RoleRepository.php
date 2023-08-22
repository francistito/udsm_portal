<?php

namespace App\Repositories\Access;


use App\Models\Access\Role;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository
{
    const MODEL = Role::class;


    public function  getDetail($id){

        $role = $this->query()->where('id', $id)->first();
        return $role;
    }

    public function forSelect()
    {
        return $this->query()->where('isactive', 1)->pluck('name', 'id');
    }

    public function getNonAdministrativeRolesForSelect()
    {
        return $this->query()->select(['id', 'name'])->where("isadmin", 0)->orderBy("id", "asc")->get()->pluck("name", "id");;
    }

    /*Get Administrative roles for select*/
    public function getAdministrativeRolesForSelect()
    {
        return $this->query()->select(['id', 'name'])->where("isadmin", 1)->orderBy("id", "asc")->get()->pluck("name", "id");;
    }



    /*Get roles for datatable*/
    public function getRolesForDataTable()
    {
        return $this->query();
    }


    /**
     * @param array $input
     * @return mixed
     * Store new role
     */
    public function store(array $input)
    {
        return  DB :: transaction(function() use ($input){
            $role = $this->query()->create([
                'id' => $this->getNextId(),
                'name' => $input['name'],
                'description' => $input['description'],
                'isadmin' => isset($input['isadmin']) ? $input['isadmin'] : 0,
            ]);
            return $role;
        });
    }



    /*Update Role and Permissions */
    public function update(array $input, Model $role)
    {
        return  DB :: transaction(function() use ($input, $role){
            $this->updateRole($input, $role);
            $this->updateRolePermissions($input, $role);
            return $role;
        });

    }


    /*Update role info to Role table*/
    public function updateRole(array $input, Model $role)
    {
        return  DB :: transaction(function() use ($input, $role){
            $role->update([
                'name' => $input['name'],
                'description' => $input['description'],
                'isadmin' => isset($input['isadmin']) ? $input['isadmin'] : 0,
            ]);
            return $role;
        });
    }


    /*Update sync permissions with role*/
    public function updateRolePermissions(array $input, Model $role)
    {
        return  DB :: transaction(function() use ($input, $role){
            $permissions = [];

            foreach ($input as $key => $value) {
                if (strpos($key, 'permission') !== false) {
                    $permission_id = substr($key, 10);
                    array_push($permissions, $permission_id);
                }
            };

            $role->permissions()->sync($permissions);

            return $role;
        });
    }


    /*Get The max id*/
    public function getMaxId()
    {
        return $this->query()->max('id');
    }

    /*Get the next id to be used on the new entry*/
    public function getNextId()
    {
        return $this->getMaxId() + 1;
    }


    /**
     * Auto crea
     */
    public function autoCreateDefaultRolesOther()
    {

        $check = $this->query()->where('isdefault_other', 1)->count();
        if($check == 0)
        {
            /*sales officer*/
//            if(sysdef()->licensedModules()['sales']){
                $this->createSalesOfficerRolePermission();
//            }

            /*hr officer*/
//            if(sysdef()->licensedModules()['hr']) {
                $this->createHrOfficerRolePermission();
//            }
        }
    }

    /**
     * Accountant Officer
     */
    public function createSalesOfficerRolePermission()
    {
        $input = [
            'name' => 'Sales Officer',
            'description' => 'Manage Sales transactions',
            'isactive' => '1',
            'isadmin' => '0',
            'isdefault_other' => '1',
        ];
        $role = $this->query()->create($input);

        $role->permissions()->syncWithoutDetaching([22,67,70,74,77,135,136,137,138,139,140,141,142,143,144,146,148,149,150,153,154,164,176,177,179,194]);
    }


    /**
     * Sync HR officer role
     */
    public function createHrOfficerRolePermission()
    {
        $input = [
            'name' => 'HR Officer',
            'description' => 'Manage Employee and Payroll',
            'isactive' => '1',
            'isadmin' => '0',
            'isdefault_other' => '1',
        ];
        $role = $this->query()->create($input);

        $role->permissions()->syncWithoutDetaching([10,17,18,22,27,28,29,30,31,32,33,34,35,36,53,57,58,72,73,77,82,92,93,94,95,96,97,98,99,104,106,114,119,175,184,185,191, 202,203,204,205]);
    }

}
