<?php
namespace Database\seeds\Version100;

use Illuminate\Database\Seeder;
use Database\Traits\TruncateTable;
use Database\Traits\DisableForeignKeys;

class DocumentsTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        $this->disableForeignKeys("documents");
        $this->delete('documents');

        \DB::table('documents')->insert(array (
            0 => array (
                    'id' => 1,
                    'name' => 'Blog photos',
                    'document_group_id' => 1,
                    'description' => 'This are blog pictures',
                    'isrecurring' => 1,
                    'ismandatory' => 1,
                    'isrenewable' => 1,
                    'isactive' => 1,
                ),
            1 => array (
                    'id' => 2,
                    'name' => 'Client logo',
                    'document_group_id' => 2,
                    'description' => 'This is client logo',
                    'isrecurring' => 1,
                    'ismandatory' => 1,
                    'isrenewable' => 1,
                    'isactive' => 1,
                ),
            2 => array (
                    'id' => 3,
                    'name' => 'Module Functional Part Images',
                    'document_group_id' => 3,
                    'description' => 'This is module functional parts images',
                    'isrecurring' => 1,
                    'ismandatory' => 1,
                    'isrenewable' => 1,
                    'isactive' => 1,
                ),
            3 => array (
                'id' => 4,
                'name' => 'Service attachment',
                'document_group_id' => 4,
                'description' => 'This is services documents',
                'isrecurring' => 1,
                'ismandatory' => 1,
                'isrenewable' => 1,
                'isactive' => 1,
            ),
            4 => array (
                'id' => 5,
                'name' => 'Training image',
                'document_group_id' => 5,
                'description' => 'This is training image',
                'isrecurring' => 1,
                'ismandatory' => 1,
                'isrenewable' => 1,
                'isactive' => 1,
            ),
            5 => array (
                'id' => 6,
                'name' => 'Training documents',
                'document_group_id' => 5,
                'description' => 'This is training documents',
                'isrecurring' => 1,
                'ismandatory' => 1,
                'isrenewable' => 1,
                'isactive' => 1,
            ),
            6 => array (
                'id' => 7,
                'name' => 'Slider Images',
                'document_group_id' => 6,
                'description' => 'This is slider images',
                'isrecurring' => 1,
                'ismandatory' => 1,
                'isrenewable' => 1,
                'isactive' => 1,
            ),

            7 => array (
                'id' => 8,
                'name' => 'Carrier Images',
                'document_group_id' => 7,
                'description' => 'This is carrier images',
                'isrecurring' => 1,
                'ismandatory' => 1,
                'isrenewable' => 1,
                'isactive' => 1,
            ),

        ));

        $this->enableForeignKeys("documents");

    }
}
