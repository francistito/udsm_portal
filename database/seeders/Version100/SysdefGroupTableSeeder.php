<?php
namespace Database\Seeders\Version100;

use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;

use App\Models\Sysdef\Sysdef;

class SysdefGroupTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys("sysdef_groups");
        $this->delete('sysdef_groups');

        \DB::table('sysdef_groups')->insert(array (

            0 =>
                array (
                    'id' => 1,
                    'name' => 'Organisation',
                    'description' => 'Manage organisation definition',

                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Threshold',
                    'description' => 'Manage threshold definitions',

                ),

            2 =>
                array (
                    'id' => 3,
                    'name' => 'Features',
                    'description' => 'Manage features',

                ),



        ));
        $this->enableForeignKeys("sysdef_groups");

    }
}
