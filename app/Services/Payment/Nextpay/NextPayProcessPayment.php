<?php


namespace App\Services\Payment\Nextpay;


class NextPayProcessPayment
{
    public function create($msisdn, $amount, $invoice)
    {
        //The XML string that you want to send.
     $xml = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?>
<Command>
<CustomerMSISDN>{$msisdn}</CustomerMSISDN>
<Amount>{$amount}</Amount>
<ThirdPartyID>NXT01</ThirdPartyID>
<CustomerRefId>{$invoice}</CustomerRefId>
<Remarks>Nextbyte SMS subscription</Remarks>
</Command>
XML;


        //The URL that you want to send your XML to.
        $url = url("http://167.172.239.127:8989/gateway/ussdpush");
        //$url = url("http://cpanel.vusha.co.tz:8989/gateway/ussdpush");

        //Initiate cURL
        $curl = curl_init($url);

        //Set the Content-Type to text/xml.
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

        //Set CURLOPT_POST to true to send a POST request.
        curl_setopt($curl, CURLOPT_POST, true);

        //Attach the XML string to the body of our request.
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

        //Tell cURL that we want the response to be returned as
        //a string instead of being dumped to the output.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //Execute the POST request and send our XML.
        $response = curl_exec($curl);

        //Do some basic error checking.
        $err = curl_error($curl);

        //Close the cURL handle.
        curl_close($curl);

        if ($err) {
            $result = "cURL Error #:" . $err;
        } else {
            //Print out the response output.
            return $result = $response;
        }
        return $result;
    }

}
