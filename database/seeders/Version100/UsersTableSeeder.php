<?php
namespace Database\Seeders\Version100;

use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

//        DB::table('users')->delete();
        $this->disableForeignKeys("users");
//        $this->delete('users');
//       DB::table('users')->where('id', 3)->update(['id' => 1]);


        $userRepo = new \App\Repositories\Access\UserRepository();
        $exists = $userRepo->query()->count();

        if($exists == 0) {
            $user = $userRepo->query()->firstOrCreate([
                'username' => 'admin',
                'firstname' => 'Administrator',
                'lastname' => 'Administrator',
                'middlename' => 'Administrator',
                'email' => 'admin@vmg.co.tz',
                'phone' => '+255700000000',
                'password' => bcrypt('vmg123?'),
                'confirmed' => '1',
            ]);
        }
//
//        $this->disableForeignKeys('staffs');
//        $staff = new \App\Repositories\Staff\StaffRepository();
//        $staff->query()->updateOrCreate([
//            'user_id' => $user->id,
//            'staff_identity' => 'Tbs-001',
//            'firstname' => 'Admin',
//            'lastname' => 'TBS',
//            'email' => 'admin@tbs-oas.go.tz',
//            'created_by' => '0',
//            'designation_id' => 6,
//            'unit_id' => 2,
//            'port_id' => 1,
//        ]);
//        $this->enableForeignKeys("staffs");
//
//        $this->disableForeignKeys('role_user');
//        \DB::table('role_user')->where('user_id', $user->id)->delete();
//
//
//        \DB::table('role_user')->insert(array (
//            0 =>
//                array(
//                    'id'      => '1',
//                    'user_id' => $user->id,
//                    'role_id' => '1'
//                )
//        ));
        $this->enableForeignKeys("role_user");

    }
}

