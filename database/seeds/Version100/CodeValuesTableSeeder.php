<?php
namespace Database\seeds\Version100;

use Cassandra\Schema;
use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;

use App\Models\System\CodeValue;
use Illuminate\Support\Facades\DB;

class CodeValuesTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        $this->disableForeignKeys("code_values");

        CodeValue::query()->delete();

        DB::table('code_values')->where('id',6)->delete();
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'ULLGI'],
            [
                'id' => 1,
                'code_id' => 1,
                'name' => 'Log In',
                'lang' => NULL,
                'description' => '',
                'reference' => 'ULLGI',
                'sort' => 1,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'ULLGO'],
            [
                'id' => 2,
                'code_id' => 1,
                'name' => 'Log Out',
                'lang' => NULL,
                'description' => '',
                'reference' => 'ULLGO',
                'sort' => 2,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'ULFLI'],
            [
                'id' => 3,
                'code_id' => 1,
                'name' => 'Failed Log In',
                'lang' => NULL,
                'description' => '',
                'reference' => 'ULFLI',
                'sort' => 3,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'ULPSR'],
            [
                'id' => 4,
                'code_id' => 1,
                'name' => 'Password Reset',
                'lang' => NULL,
                'description' => '',
                'reference' => 'ULPSR',
                'sort' => 4,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'ULULC'],
            [
                'id' => 5,
                'code_id' => 1,
                'name' => 'User Lockout',
                'lang' => NULL,
                'description' => '',
                'reference' => 'ULULC',
                'sort' => 5,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'GNDMALE'],
            [
                'id' => 6,
                'code_id' => 3,
                'name' => 'Male',
                'lang' => NULL,
                'description' => '',
                'reference' => 'GNDMALE',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'GNDFMALE'],
            [
                'id' => 7,
                'code_id' => 3,
                'name' => 'Female',
                'lang' => NULL,
                'description' => '',
                'reference' => 'GNDFMALE',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );


  $cv = CodeValue::updateOrCreate(
            ['reference' => 'RACTYPIND'],
            [
                'id' => 8,
                'code_id' => 4,
                'name' => 'Individual',
                'lang' => NULL,
                'description' => '',
                'reference' => 'RACTYPIND',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
  $cv = CodeValue::updateOrCreate(
            ['reference' => 'RACTYPGRP'],
            [
                'id' => 9,
                'code_id' => 4,
                'name' => 'Group',
                'lang' => NULL,
                'description' => '',
                'reference' => 'RACTYPGRP',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

  $cv = CodeValue::updateOrCreate(
            ['reference' => 'TTYPEDRFT'],
            [
                'id' => 10,
                'code_id' => 5,
                'name' => 'Dri-Fit T-shirt',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TTYPEDRFT',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZEXXL'],
            [
                'id' => 11,
                'code_id' => 6,
                'name' => 'XXL',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZEXXL',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZEXL'],
            [
                'id' => 12,
                'code_id' => 6,
                'name' => 'XL',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZEXL',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZEL'],
            [
                'id' => 13,
                'code_id' => 6,
                'name' => 'L',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZEL',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZEM'],
            [
                'id' => 14,
                'code_id' => 6,
                'name' => 'M',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZEM',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZES'],
            [
                'id' => 15,
                'code_id' => 6,
                'name' => 'S',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZES',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'TSIZEXS'],
            [
                'id' => 16,
                'code_id' => 6,
                'name' => 'XS',
                'lang' => NULL,
                'description' => '',
                'reference' => 'TSIZEXS',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'RACECAT5KM'],
            [
                'id' => 17,
                'code_id' => 7,
                'name' => '5KM',
                'lang' => NULL,
                'description' => '',
                'reference' => 'RACECAT5KM',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'RACCT10KM'],
            [
                'id' => 18,
                'code_id' => 7,
                'name' => '10KM',
                'lang' => NULL,
                'description' => '',
                'reference' => 'RACCT10KM',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'RACCT21KM'],
            [
                'id' => 19,
                'code_id' => 7,
                'name' => '21KM',
                'lang' => NULL,
                'description' => '',
                'reference' => 'RACCT21KM',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $this->enableForeignKeys("code_values");


    }
}
