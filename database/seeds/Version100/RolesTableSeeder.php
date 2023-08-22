<?php
namespace Database\seeds;

use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    use DisableForeignKeys,TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


//        $role = (new \App\Repositories\Access\RoleRepository())->query()->firstOrCreate(  ['id' => '1'],[
//            'name' => 'Admin',
//            'description' => 'Administrator',
//            'isactive' => '1',
//            'isadmin' => '1',
//        ]);

    }
}
