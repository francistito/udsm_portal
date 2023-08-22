<?php
namespace Database\seeds\Version100;

use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CodesTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        $this->disableForeignKeys("codes");
        $this->delete('codes');

        DB::table('codes')->insert(array (

            0 => array (
                'id' => 1,
                'name' => 'User Logs',
                'lang' => 'user_log',
                'is_system_defined' => 1,
            ),
            1 => array (
                'id' => 2,
                'name' => 'Service Types',
                'lang' => 'service_type',
                'is_system_defined' => 1,
            ),
            2 => array (
                'id' => 3,
                'name' => 'Gender',
                'lang' => 'gender',
                'is_system_defined' => 1,
            ),

            3 => array (
                'id' => 4,
                'name' => 'Race types',
                'lang' => 'race_types',
                'is_system_defined' => 1,
            ),
            4 => array (
                'id' => 5,
                'name' => 'Tshirt types',
                'lang' => 'race_types',
                'is_system_defined' => 1,
            ),
            5 => array (
                'id' => 6,
                'name' => 'Tshirt size',
                'lang' => 'race_types',
                'is_system_defined' => 1,
            ),
     6 => array (
                'id' => 7,
                'name' => 'Race category',
                'lang' => 'race_types',
                'is_system_defined' => 1,
            ),



        ));

        $this->enableForeignKeys("codes");
    }
}
