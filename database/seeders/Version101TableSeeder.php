<?php
namespace Database\Seeders;


use CountriesTableSeeder;
use CurrenciesTableSeeder;
use Database\Seeders\Version100\CodesTableSeeder;
use Database\Seeders\Version100\CodeValuesTableSeeder;
use Database\Seeders\Version100\DesignationsTableSeeder;
use Database\Seeders\Version100\DocumentGroupsTableSeeder;
use Database\Seeders\Version100\DocumentsTableSeeder;
use Database\Seeders\Version100\SysdefGroupTableSeeder;
use Database\Seeders\Version100\SysdefsTableSeeder;
use Database\Seeders\Version100\TrainingCategoriesTableSeeder;
use Database\Seeders\Version100\UnitGroupsTableSeeder;
use Database\Seeders\Version100\UnitsTableSeeder;
use Database\Seeders\Version100\UsersTableSeeder;
use DistrictsTableSeeder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



/**
 * Class AccessTableSeeder.
 */
class Version101TableSeeder extends Seeder
{
//    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //disable foreign key check for this connection before running seeders

        DB::beginTransaction();


//        $this->call(RolesTableSeeder::class);
//        $this->call(PermissionGroupTableSeeder::class);
//        $this->call(PermissionsTableSeeder::class);
//        $this->call(PermissionRoleTableSeeder::class);
        $this->call(CodesTableSeeder::class);
        $this->call(CodeValuesTableSeeder::class);
        $this->call(DocumentGroupsTableSeeder::class);
        $this->call(DocumentsTableSeeder::class);
        $this->call(DesignationsTableSeeder::class);
        $this->call(UnitGroupsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(SysdefGroupTableSeeder::class);
        $this->call(SysdefsTableSeeder::class);
//        $this->call(CurrenciesTableSeeder::class);
//        $this->call(CountriesTableSeeder::class);
//        $this->call(RegionsTableSeeder::class);
//        $this->call(DistrictsTableSeeder::class);
//        $this->call(ReportGroupTableSeeder::class);
//        $this->call(ReportTypesTableSeeder::class);
//        $this->call(ReportsTableSeeder::class);
//        $this->call(ReportGroupReportTableSeeder::class);
//        $this->call(ModuleGroupTableSeeder::class);
//        $this->call(ModulesTableSeeder::class);
        $this->call(TrainingCategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        DB::commit();

    }
}
