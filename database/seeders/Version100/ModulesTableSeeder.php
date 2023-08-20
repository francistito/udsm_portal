<?php

use Illuminate\Database\Seeder;
use Database\TruncateTable;
use Database\DisableForeignKeys;
use \App\Models\Cms\Module;

class ModulesTableSeeder extends Seeder
{

    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys('modules');

        $module = Module::updateOrCreate(
            array ( 'id' => 1),
            array (
                'id' => 1,
                'name' => 'Client management',
                'module_group_id' => '1',
                'created_at' => '2019-01-27 08:27:49',
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'isactive' => '1',
                'description' => '',
            )
        );

        $module = Module::updateOrCreate(
            array ( 'id' => 2),
            array (
                'id' => 2,
                'name' => 'Supplier management',
                'module_group_id' => '1',
                'created_at' => '2019-01-27 08:27:49',
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'isactive' => '1',
                'description' => '',
            )
        );


        $this->enableForeignKeys('modules');
    }
}
