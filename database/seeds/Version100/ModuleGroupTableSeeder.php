<?php

use Illuminate\Database\Seeder;
use Database\TruncateTable;
use Database\DisableForeignKeys;

class ModuleGroupTableSeeder extends Seeder
{

    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys('module_groups');
        $this->delete('module_groups');

        \DB::table('module_groups')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Hr management',
                    'description' => 'Special',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Operation',
                    'description' => 'Special',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            3 =>
                array (
                    'id' => 13,
                    'name' => 'Report',
                    'description' => 'Special',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            4 =>
                array (
                    'id' => 14,
                    'name' => 'Workflow and alert monitor',
                    'description' => 'All workflow and alert monitor',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            5 =>
                array (
                    'id' => 7,
                    'name' => 'Employee management',
                    'description' => 'Manage employee',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            6 =>
                array (
                    'id' => 5,
                    'name' => 'Account',
                    'description' => 'Manage account',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            7 =>
                array (
                    'id' => 6,
                    'name' => 'Stock management',
                    'description' => 'Manage employee',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            8 =>
                array (
                    'id' => 8,
                    'name' => 'Payroll management',
                    'description' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),

        ));

        $this->enableForeignKeys('module_groups');
    }
}
