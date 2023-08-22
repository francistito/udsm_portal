<?php


namespace App\Services\Access\SystemSecurity;


use App\Http\Controllers\Operation\Client\Traits\SupplierControllerTrait;
use App\Models\Operation\Client\SubClient;
use App\Models\Operation\Client\Traits\Attributes\ClientAttribute;
use App\Models\Operation\Loan\ClientLoanRepayment;
use App\Models\Operation\Loan\Creditor;
use App\Models\Operation\Sales\ClientDiscount;
use App\Models\Operation\Sales\DirectSale;
use App\Models\System\Document;
use App\Models\System\Region;

trait DatabaseInformation
{


    /*Database Credentials*/
    public function databaseCredentials($data)
    {

        $info_arr = [
            'DB_USERNAME' => 'postgres',
            'DB_PASSWORD' => 'nextbyte'
        ];
        return $info_arr[$data];
    }

}
