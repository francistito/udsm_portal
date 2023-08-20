<?php
namespace Database\Seeders\Version100;

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

        $cv = CodeValue::firstOrCreate(
            ['reference' => 'SERTYPEHR'],
            [
                'id' => 6,
                'code_id' => 2,
                'name' => 'HUMAN RESOURCE MANAGEMENT',
                'lang' => NULL,
                'description' => '
        
                
                At VMG our Human Resource Management functions can be classified into the following three categories.

        1: Local Staff Management:
                Our company will provide HUMAN RESOURCE MANAGER who will be responsible to handle and manage all local employees with maximizing productivity and protecting the company from any issues that may arise within the workforce. Our service frees our clients from stress and labour union hassles and assures them of high capacity utilization and productivity at all times.
                
               2: Foreigner Expert Management:
Our company will provide HR Outsourcing Function with greater efficiency within human resources system; such as payroll benefit administration, and compliance management. As well will be responsible to manage professionals, skilled and technical personnel (welders) at all levels on a short to long-term basis. VMG will acquire your existing employees into our organization and provide them with full management support.

        Scope of Work
We make our offering specifically to your needs including the following services:
-> HR Compliance Reviews;
-> Recruitment Process;
-> Employee Relation;
-> Training and Development;
-> Payroll and benefit administration;
-> Administration of the whole employment lifecycle e.g. employment contract;
-> Performance management;
-> Rewards and benefits;
-> Policy development.
',
                'reference' => 'SERTYPEHR',
                'sort' => 1,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'SERTYPELW'],
            [
                'id' => 7,
                'code_id' => 2,
                'name' => 'LEGAL COMPLIANCE',
                'lang' => NULL,
                'description' => '
        

',                'reference' => 'SERTYPELW',
                'sort' => 2,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'SERTYPREC'],
            [
                'id' => 8,
                'code_id' => 2,
                'name' => 'RECRUITMENT',
                'lang' => NULL,
                'description' => '',
                'reference' => 'SERTYPREC',
                'sort' => 3,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'SERTYPFINA'],
            [
                'id' => 9,
                'code_id' => 2,
                'name' => 'FINANCIAL MANAGEMENT',
                'lang' => NULL,
                'description' => '
        
                           Our Audit, Tax, and Business Consulting service lines are built on set of pragmatic solutions based on deep and relevant industry experience. Partners and employees of Vipawa are proud to have gathered vast experiences from a wide range of clients, from multinational subsidiaries in Tanzania, government and non-government projects, to entrepreneurial establishments.
                
            Directors, Partners and employees of Vipawa have served clients across East Africa, Europe and North America while working for the market-labelled “big-four” professional advisory firms and in the industry. The partners and employees have over eight years of experience with the big-four firms and other multinational companies among them spanning from Africa, Europe and North America. With this regional and international exposure Vipawa is able to draw from a pool of skills and experience, enabling us to provide robust, value adding and high-quality services to our clients. We are also able to offer coordinated services where the client is represented in more than one location in East Africa through corresponding partners in other East African countries.

',                     'reference' => 'SERTYPFINA',
                'sort' => 4,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'SERTYPETRA'],
            [
                'id' => 10,
                'code_id' => 2,
                'name' => 'INFORMATION TECHNOLOGY',
                'lang' => NULL,
                'description' => '',
                'reference' => 'SERTYPETRA',
                'sort' => 5,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'SERTYPECO'],
            [
                'id' => 11,
                'code_id' => 2,
                'name' => 'COMPLIANCE MANAGEMENT',
                'lang' => NULL,
                'description' => '',
                'reference' => 'SERTYPECO',
                'sort' => 6,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );


        $cv = CodeValue::updateOrCreate(
            ['reference' => 'KNLGTOS'],
            [
                'id' => 13,
                'code_id' => 3,
                'name' => 'TOOLS & SAMPLE',
                'lang' => NULL,
                'description' => '',
                'reference' => 'KNLGTOS',
                'sort' => 8,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'KNLGHR'],
            [
                'id' => 14,
                'code_id' => 3,
                'name' => 'HR TOPICS',
                'lang' => NULL,
                'description' => '',
                'reference' => 'KNLGHR',
                'sort' => 9,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );
        $cv = CodeValue::updateOrCreate(
            ['reference' => 'KNLGBUSS'],
            [
                'id' => 15,
                'code_id' => 3,
                'name' => 'BUSINESS SOLUTION',
                'lang' => NULL,
                'description' => '',
                'reference' => 'KNLGBUSS',
                'sort' => 10,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $cv = CodeValue::updateOrCreate(
            ['reference' => 'KNLGLEC'],
            [
                'id' => 16,
                'code_id' => 3,
                'name' => 'LEGAL & COMPLIANCE',
                'lang' => NULL,
                'description' => '',
                'reference' => 'KNLGLEC',
                'sort' => 11,
                'isactive' => 1,
                'is_system_defined' => 1,
            ]
        );

        $this->enableForeignKeys("code_values");


    }
}
