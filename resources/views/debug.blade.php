<?php

use Bryceandy\Selcom\Facades\Selcom;

$selecom =Selcom::checkout([
    'name' => "Buyer's full name",
    'email' => "Buyer's email",
    'phone' => "Buyer's msisdn, for example 255756334000",
    'amount' => "Amount to be paid",
    'transaction_id' => "Unique transaction id",
    'no_redirection' => true,
    // Optional fields
    'currency' => 'Default is TZS',
    'items' => 'Number of items purchased, default is 1',
    'payment_phone' => 'The number that will make the USSD transactions, if not specified it will use the phone value',
]);


dd($selecom);
