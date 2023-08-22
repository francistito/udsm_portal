<?php
namespace Database\seeds\Version100;

use App\Models\Resource\TrainingCategory;
use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingCategoriesTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

            $training_category = TrainingCategory::firstOrCreate(
                ['id' => 1],
                [
                    'name' => 'Accountant',
                    'short_name' => 'acc',
                    'lang' => 'accountant',
                    'isactive' => 1,
                    'created_at' => '2018-10-28 14:54:12',
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ]
            );
        $training_category = TrainingCategory::firstOrCreate(
            ['id' => 2],
            [
                'name' => 'Technology',
                'short_name' => 'tech',
                'lang' => 'technology',
                'isactive' => 1,
                'created_at' => '2018-10-28 14:54:12',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ]
        );



//
        $this->enableForeignKeys("role_user");

    }
}

