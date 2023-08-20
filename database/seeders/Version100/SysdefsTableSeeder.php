<?php
namespace Database\Seeders\Version100;

use Database\Traits\DisableForeignKeys;
use Database\Traits\TruncateTable;
use Illuminate\Database\Seeder;

use App\Models\System\Sysdef;

class SysdefsTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {




        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSTEL'],
            [
                'name' => 'telephone',
                'display_name' => 'Telephone',
                'value' => '0717 443 616',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'MAXEPU',
                'sysdef_group_id' => 1,
            ]
        );

        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSMOB'],
            [
                'name' => 'mobile_phone',
                'display_name' => 'Mobile Phone',
                'value' => '+255 717 443 616 ',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYSMOB',
                'sysdef_group_id' => 1,
            ]
        );

        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSPOB'],
            [
                'name' => 'po_box',
                'display_name' => 'P.O Box',
                'value' => '32080 ',
                'data_type' => 'integer',
                'isactive' => 1,
                'reference' => 'SYSPOB',
                'sysdef_group_id' => 1,
            ]
        );

        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSLOC'],
            [
                'name' => 'location',
                'display_name' => 'Location',
                'value' => ' Chabruma Street, Ali Hassan Mwinyi Road, JANGID Plaza, 3rd Floor, Room no. 303',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYSLOC',
                'sysdef_group_id' => 1,
            ]
        );

        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSEMA'],
            [
                'name' => 'email',
                'display_name' => 'Email',
                'value' => ' info@vipaji.co.tz',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYSEMA',
                'sysdef_group_id' => 1,
            ]
        );


        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSSEM'],
            [
                'name' => 'support_email',
                'display_name' => 'Support Email',
                'value' => ' info@vipaji.co.tz',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYSSEM',
                'sysdef_group_id' => 1,
            ]
        );

        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'MAXLOS'],
            [
                'name' => 'max_logo_size',
                'display_name' => 'Max logo size (MB)',
                'value' => 3,
                'data_type' => 'integer',
                'isactive' => 1,
                'reference' => 'MAXLOS',
                'sysdef_group_id' => 2,
            ]
        );



        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYDORGN'],
            [
                'name' => 'organisation_name',
                'display_name' => 'Organisation Name',
                'value' => 'VIPAWA MANAGEMENT GROUP',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYDORGN',
                'sysdef_group_id' => 1,
            ]
        );


        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYDABT'],
            [
                'name' => 'about_vmg',
                'display_name' => 'About VMG',
                'value' => 'VIPAWA MANAGEMENT has been established as a management and human resource consultancy in Tanzania. It is a consultative advisory organization on employment, management, and labour related matters. The company traditional business model is based on the accomplishment of management and human resources services. Based on the decision of the company to diversify our services; we have now established this company in Dar es Salaam',
                'data_type' => 'text',
                'isactive' => 1,
                'reference' => 'SYDABT',
                'sysdef_group_id' => 1,
            ]
        );


        $sysdef = Sysdef::firstOrCreate(
            ['reference' => 'SYSNAME'],
            [
                'name' => 'system_name',
                'display_name' => 'System Name',
                'value' => 'VMG',
                'data_type' => 'string',
                'isactive' => 1,
                'reference' => 'SYSNAME',
                'sysdef_group_id' => 1,
            ]
        );








        /*update this definition - SYSFU*/
        $input = ['name' => 'activate_service_feature_advert' , 'display_name' => 'Activate feature for Service i.e Advertisement'];
        $this->updateExisting('SYSFU', $input);

    }


    /**
     * Update model existing with specified column only system fields
     */
    public function updateExisting($reference,array $input)
    {
        $sysdef = Sysdef::query()->where('reference', $reference)->first();
        if(isset($sysdef))
        {
            $sysdef->update([
                'name' => isset($input['name']) ? $input['name'] : $sysdef->name,
                'display_name' =>  isset($input['display_name']) ? $input['display_name'] : $sysdef->display_name,
                'data_type' =>  isset($input['data_type']) ? $input['data_type'] : $sysdef->data_type,
                'isactive' =>  isset($input['isactive']) ? $input['isactive'] : $sysdef->isactive,
                'sysdef_group_id' =>  isset($input['sysdef_group_id']) ? $input['sysdef_group_id'] : $sysdef->sysdef_group_id,
            ]);
        }
    }

}
