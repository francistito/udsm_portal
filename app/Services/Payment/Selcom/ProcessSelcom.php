<?php

namespace App\Services\Payment\Selcom;

use Carbon\Carbon;

/**
 * Class CreatePayment
 * @package App\Services\Payment\Selcom
 * @author Erick M. Chrysostom
 */
class ProcessSelcom
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var
     */
    protected $url;

    /**
     * @var string
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $api_secret;

    /**
     * @var
     */
    protected $signed_fields;

    /**
     * CreatePayment constructor.
     */
    public function __construct()
    {
        $this->api_key = "NEXTBYTE-8Plj0rPnRhVUkUSa";
        $this->api_secret = "58974521-9J21-4J13-aT85-a78c1b158745";
        //$this->timestamp = Carbon::now()->format("Y-m-d H:i:s");
        $this->timestamp = date('c');
    }

    /**
     * @param array $params | order_id, amount, currency, payer_remarks, merchant_remarks, payer_email, payer_phone
     * @return mixed
     */
    function create(array $params) {
        $this->params = json_encode($params);
        $this->url = "https://apigw.selcommobile.com/v1/checkout/create-order";
        $this->signed_fields = "vendor,order_id,buyer_email,buyer_name,buyer_phone,amount,currency,redirect_url,cancel_url,webhook,buyer_remarks,merchant_remarks,no_of_items,header_colour,link_colour,button_colour,buyer_userid,gateway_buyer_uuid,payment_methods,billing.firstname,billing.lastname,billing.address_1,billing.address_2,billing.city,billing.state_or_region,billing.postcode_or_pobox,billing.country,billing.phone";
        $response = $this->sendJSONPost();
        //$response = $this->sendRequest();
        //$response = '{"reference" : "0289999288", "resultcode" : "000", "result" : "SUCCESS", "message" : "Order creation successful", "data": [{"gateway_buyer_uuid":"12344321", "payment_token":"80008000", "qr":"QR", "payment_gateway_url":"aHR0cDpleGFtcGxlLmNvbS9wZy90MTIyMjI="}]}';
        return $response;
    }

    /**
     * @param $order
     * @return mixed
     */
    public function status($order, array $params)
    {
        $this->params = json_encode($params);
        $this->url = "https://apigw.selcommobile.com/v1/checkout/order-status?order_id={$order}";
        $this->signed_fields = "order_id";
        $response = $this->sendJSONPost(false);
        //$response = $this->sendRequest();
        //$response = '{"reference" : "0289999288", "resultcode" : "000", "result" : "SUCCESS", "message" : "Order fetch successful", "data": [{"order_id":"123", "creation_date":"2019-06-06 22:00:00", "amount":"1000", "payment_status":"PENDING","transid":null,"channel":null,"reference":null,"phone":null}]}';
        return $response;
    }

    /**
     * @param $order
     * @return mixed
     */
    public function cancel($order, array $params)
    {
        $this->params = json_encode($params);
        $this->url = "https://apigw.selcommobile.com/v1/checkout/cancel-order?order_id={$order}";
        $this->signed_fields = 'order_id';
        $response = $this->sendJSONPost(true, 'DELETE');
        //$response = $this->sendRequest();
        //$response = '{"reference" : "0289999288", "resultcode" : "000", "result" : "SUCCESS", "message" : "Order cancellation successful", "data": []}';
        return $response;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function walletPull(array $params)
    {
        $this->params = json_encode($params);
        $this->url = 'https://apigw.selcommobile.com/v1/checkout/wallet-payment';
        $this->signed_fields = 'order_id,transid,msisdn';
        $response = $this->sendJSONPost();
        //$response = $this->sendRequest();
        //$response = '{"result" : "SUCCESS"}';
        return $response;
    }

    /**
     * @param $timestamp
     * @param $api_key
     * @param $api_secret
     * @return string
     * @deprecated
     */
    public function getDigest($timestamp, $api_key, $api_secret) {
        return md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));
    }

    public function sendJSONPost($isPost = true, $customRequest = '') {


        $json = $this->params;
        $url = $this->url;
        $timestamp = $this->timestamp;
        $authorization = base64_encode($this->api_key);
        $signed_fields = $this->signed_fields;
        $digest = $this->computeSignature(json_decode($json, true), $signed_fields, $timestamp, $this->api_secret);

        $headers = array(
            "Content-type: application/json;charset=\"utf-8\"",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Authorization: SELCOM $authorization",
            "Digest: $digest",
            "Digest-Method: HS256",
            "Timestamp: $timestamp",
            "Signed-Fields: $signed_fields",
        );

        //print_r($headers);
        //$headers =  array("Content-type: application/json");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        //echo $json;

        if($isPost) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        if ($customRequest) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customRequest);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_TIMEOUT,90);
        //$information = curl_getinfo($ch);
        //print_r($information);
        $result = curl_exec($ch);
        $result;
        curl_close($ch);

        //$resp = json_decode($result, true);
        $resp = $result;

        //$resp['result_code'] = zeropad($resp['result_code'], 3);
        return $resp;
    }

    public function computeSignature($parameters, $signed_fields, $request_timestamp, $api_secret): string
    {
        $fields_order = explode(',', $signed_fields);
        $sign_data = "timestamp=$request_timestamp";
        foreach ($fields_order as $key) {
            $key_arr = explode(".", $key);
            $tkey = "";
            if(count($key_arr)==1)
            {
                $sign_data .= "&$key=".$parameters[$key];
            }else if(count($key_arr)==2){
                $tkey = $key_arr[0].".".$key_arr[1];
                $sign_data .= "&$key=".$parameters[$key_arr[0]][$key_arr[1]];
            }else{
                throw Exception("Request dimension not supported");
            }

        }

        $sign_data."#";

        return base64_encode(hash_hmac('sha256', $sign_data, $api_secret, true));
        //RS256 Signature Method
        /*$private_key_pem = openssl_get_privatekey(file_get_contents("./private.key"));
        openssl_sign($sign_data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
        */
    }

    /**
     * @return mixed
     * @deprecated
     */
    public function sendRequest()
    {
        $api_digest = $this->getDigest($this->timestamp, $this->api_key, $this->api_secret);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "api_key: {$this->api_key}",
            "digest: {$api_digest}",
            "request_timestamp: {$this->timestamp}"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
