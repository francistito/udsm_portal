<?php

namespace App\Repositories\Payment;


use App\Events\Payment\PaidInvoice;
use App\Jobs\Notifications\SendSms;
use App\Models\Acess\User;
use App\Models\Payment\Payment;
use App\Models\Product\Product;
use App\Notifications\Payment\SendPaymentReferenceNumber;
use App\Repositories\BaseRepository;
use App\Repositories\System\CodeValueRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Pesapal;


class PaymentRepository extends BaseRepository
{

    const MODEL = Payment::class;


    public  function  __construct( )
    {
        $this->code_value = new CodeValueRepository();
    }

    /**
     * @param $input
     * @return mixed
     */
    public  function store($input){

        return DB::transaction(function () use($input) {
            $payment = $this->query()->create($input);
            return $payment;
        });
    }

    public  function  getPackageDetail($id)
    {
        $package = $this->query()->where(['id',$id])->first();
        return $package;
    }

    /*PESAPAL*/

    public function pesapalInit($invoice){
        $params = [];
        $amount = $invoice->amount;
        $amount = number_format($amount, 2);//format amount to 2 decimal places
        $type ="MERCHANT";
        $desc = $invoice->number;

        $reference =$invoice->uuid;
        $user = access()->user();
        //$first_name = $user->first_name;
        $first_name = "NextSMS Inv.";
        //$last_name = $user->last_name;
        $last_name = $invoice->number;
        $email = $user->email;
        $phonenumber =  $user->phone;

        if (!array_key_exists('currency', $params)) {
            if (config('pesapal.currency') != null) {
                $params['currency'] = config('pesapal.currency');
            }
        }

        $params = array_merge($params);

        $env_mode = (config("env.test_mode")) ? "demo" : "live";

        $token = NULL;

        $consumer_key = config("payment.pesapal.{$env_mode}.consumer_key");

        $consumer_secret = config("payment.pesapal.{$env_mode}.consumer_secret");

        $signature_method = new Pesapal\OAuthSignatureMethod_HMAC_SHA1();

        $callback_url = route('payment.confirmation',7); //redirect url, the page that will handle the response from pesapal.

        $iframelink = config("payment.pesapal.{$env_mode}.call_url");

        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"" . $amount . "\" Description=\"" . $desc . "\" Type=\"" . $type . "\" Reference=\"" . $reference . "\" FirstName=\"" . $first_name . "\" LastName=\"" . $last_name . "\" Email=\"" . $email . "\" PhoneNumber=\"" . $phonenumber . "\" xmlns=\"http://www.pesapal.com\" />";

        $post_xml = htmlentities($post_xml);

        $consumer = new Pesapal\OAuthConsumer($consumer_key, $consumer_secret);

        $iframe_src = Pesapal\OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);

        $iframe_src->set_parameter("oauth_callback", $callback_url);

        $iframe_src->set_parameter("pesapal_request_data", $post_xml);

        $iframe_src->sign_request($signature_method, $consumer, $token);

        return view('payments.payment')->with('iframe_src',$iframe_src);
    }

    public function pesapalConfirm($request, $pesapal_transaction_tracking_id, $pesapal_merchant_reference, $invoice,$source){

        $user = access()->user();
        //add payment
        $input = [
            'reference' => $pesapal_merchant_reference,
            'amount' => $invoice->amount,
            'invoice_id' => $invoice->id,
            //'code_value_id' => '94', //aggregator
            'currency_id' => $invoice->currency_id,
            'result' => '',
            'operator' => 'PESAPAL',
            'transid' => $pesapal_transaction_tracking_id,
            'utility_ref' => $pesapal_merchant_reference,
            'external_reference' => $pesapal_merchant_reference,
            'source_id' => $source->id,
            'msisdn' => $user->phone_number,
        ];
        return $this->store($input);
    }


    function checkPaymentStatus( $pesapal_merchant_reference, $pesapalTrackingId,$payment)
    {
        $env_mode = (config("env.test_mode")) ? "demo" : "live";

        $consumer_key = 'iDNCGwZNDLzBbrIM+sdvHNc8DbXKYKKp';

        $consumer_secret = 'waO9SWR+XV6rafZ46XG6jmi3/YU=';
        $statusrequestAPI = "https://demo.pesapal.com/API/QueryPaymentStatusByMerchantRef";
            $token = $params = NULL;
            $consumer = new Pesapal\OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new Pesapal\OAuthSignatureMethod_HMAC_SHA1();
            //get transaction status
            $request_status = Pesapal\OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI,
                $params);
            $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
            $request_status->set_parameter("pesapal_transaction_tracking_id", $pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED')) {
                if (CURL_PROXY_REQUIRED == 'True') {
                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                }
            }
            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];
            //transaction status
            $elements = preg_split("/=/", substr($response, $header_size));
            $status = $elements[0];
            $components = explode(',', $elements[1]);

        $transaction_id = $components[0];
            $payment_method = $components[0];
            $merchant_reference = $components[0];
            $status = $components[0];
        switch ($status) {
            case 'PENDING':

                //update payment status
                $payment->status = 'PENDING';
                $payment->save();
                break;
            case 'COMPLETE':
                //update payment status
                $payment->status = 'COMPLETE';
                $payment->save();

                //update invoice
                $invoice = $this->invoices->query()->where('uuid', $pesapal_merchant_reference)->first();
                $invoice->ispaid = 1;
                $invoice->save();

                //update customer credits
                //$this->customer->updateCustomerCredits($invoice);
                event(new PaidInvoice($invoice));

                break;
            case 'INVALID':
                //update payment status to invalid
                $payment->status = 'INVALID';
                $payment->save();
                break;

            default:
        }


        }

    /*PESAPAL END*/



    public function expertenPayment($invoice,$request)
    {
        DB::transaction(function ()use($invoice,$request){

//        $phone_number = str_replace("+", "",phone($request->input('phone_number'), $country = ['TZ'], $format = 'E164'));
//        $ussdResponse = (new NextPayProcessPayment())->create($phone_number, (int)$invoice->amount, $invoice->number);
//
//        if(strpos($ussdResponse, "cURL Error #:") === 0)
//        {
//            $invoice->error = $ussdResponse;
//            $invoice->save();
//            return redirect()->back()->withFlashDanger("Internal Service error please try again later");
//        }
//
//        $xmlResponse = simplexml_load_string($ussdResponse);
//        $responseJson = json_encode($xmlResponse);
//        $responseArray = json_decode($responseJson,TRUE);

//        $invoice->msisdn = $phone_number;
//        $invoice->message = $responseArray['Message'];
//        $invoice->payment_status = $responseArray['Status'];
//        $invoice->order = $responseJson;
            $invoice->reference_number = $request->input('reference_number');
            $invoice->status = 1;
            $invoice->save();
//        $input = [
//            'msisdn' => $phone_number,
//            'invoice_id' => $invoice->id,
//            'reference' => $responseArray['ReferenceID'],
//            'amount' => $responseArray['Amount'],
//            'status' => $responseArray['Status'],
//            'currency_id' => 1,
//            'result' => $xmlResponse,
//            'source_id' => $invoice->source_id
//        ];
//        $payment = (new PaymentRepository())->store($input);



            //send sms
            $this->sendSmsPaymentNotifications($invoice);
            //send email
//            $this->sendEmailPaymentNotifications($invoice);

        });


    }

    public function extractFilesAfterCompletePayment($invoice)
    {
        $product = Product::find($invoice->product_id);
        $category = CodeValueRepository::find($invoice->category_id);
        $category_name = $category->name;
        $sort = $product->sort;


    }

    //send sms
    public function sendSmsPaymentNotifications($invoice)
    {
        //send sms
        SendSms::dispatch($invoice, trans("label.there_is_payments").$invoice->reference_number);

    }

    //send an email
    public function sendEmailPaymentNotifications($invoice)
    {
        $users =User::whereHas(
            'roles', function($q){
            $q->where('roles.id', 1);
        }
        )->get();

        foreach ($users as $user)
        {
            //send email notification
            $user->notify(new SendPaymentReferenceNumber($invoice));

        }

    }



}
