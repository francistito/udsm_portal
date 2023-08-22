<?php
namespace Database\seeds\Version100;

use Illuminate\Database\Seeder;
use Database\Traits\TruncateTable;
use Database\Traits\DisableForeignKeys;

class DocumentGroupsTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys("document_groups");
        $this->delete('document_groups');

        \DB::table('document_groups')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Blog Documents',
                    'top_path' => '/storage/cms/blog',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Client Documents',
                    'top_path' => '/storage/cms/client',
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'User Manual Documents',
                    'top_path' => '/storage/cms/user_manual',
                ),

            3 =>
                array (
                    'id' => 4,
                    'name' => 'Service document',
                    'top_path' => '/storage/cms/services',
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Training document',
                    'top_path' => '/storage/cms/trainings',
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Slider ',
                    'top_path' => '/storage/cms/slider_images',
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'Carrier ',
                    'top_path' => '/storage/cms/carrier',
                ),
        ));
        $this->enableForeignKeys("document_groups");


        /*Make directory for top path for each doc group*/
        (new \App\Repositories\System\DocumentGroupRepository())->makeDirectoryTopPath();

    }
}
