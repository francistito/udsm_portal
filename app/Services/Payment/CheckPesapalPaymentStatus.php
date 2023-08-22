<?php


namespace App\Services\Payment;


use App\Models\Payment\Payment;
use App\Services\Pesapal\OAuthConsumer;
use App\Services\Pesapal\OAuthRequest;
use App\Services\Pesapal\OAuthSignatureMethod_HMAC_SHA1;

class CheckPesapalPaymentStatus
{

    public function __construct()
    {

    }


    public function checkStatus()
    {
        $data = Payment::latest()
            ->select('id')
            ->where('status',null)
            ->orWhere('status','PENDING')
            ->chunk(50, function($payments) {
                foreach ($payments as $payment) {

                    dispatch(new \App\Jobs\Payment\CheckPesapalPaymentStatus($payment->id));

                }
            });

    }

}
