@if(!$race_registration->ispaid && !$race_registration->iscancelled)
<div class="row">
    <div class="col-md-9">
        <div><strong>PAY WITH MASTERPASS</strong></div>
        <div class="row">
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--airtelmoney--}}
                    <div><img src="{{ url("img/nextbyte/payment/airtel-money.jpg") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc">
                        <li><strong>Dial *150*60#</strong></li>
                        <li>Choose 5-Make Payments</li>
                        <li>Choose 1-Merchant Payments</li>
                        <li>Choose 1-Pay with SelcomPay/Masterpass</li>
                        <li>Enter Amount <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter the reference number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--selcomcard--}}
                    <div><img src="{{ url("img/nextbyte/payment/selcom.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*50#</strong></li>
                        <li>Enter PIN</li>
                        <li>Choose 2 – Selcom Pay/Masterpass</li>
                        <li>Enter Pay Number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter Amount <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Please confirm payment by entering 1</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--eazypesa--}}
                    <div><img src="{{ url("img/nextbyte/payment/ezypesa.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*02#</strong></li>
                        <li>Choose 5 - Payments</li>
                        <li>Choose 1 - Lipa Hapa</li>
                        <li>Choose 2 - Pay by Masterpass QR</li>
                        <li>Enter Merchant Number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter Amount <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--halopesa--}}
                    <div><img src="{{ url("img/nextbyte/payment/halopesa.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*88#</strong></li>
                        <li>Choose 5 - Pay Merchant</li>
                        <li>Choose 3 – Selcom Pay/Masterpass</li>
                        <li>Enter Pay Number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter cost <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--tpesa--}}
                    <div><img src="{{ url("img/nextbyte/payment/tpesa.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*71#</strong></li>
                        <li>Choose 6 - Pay Merchant</li>
                        <li>Choose 2 – SelcomPay/Masterpass</li>
                        <li>Enter Pay Number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter cost <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--tigopesa--}}
                    <div><img src="{{ url("img/nextbyte/payment/tigopesa.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*01#</strong></li>
                        <li>Choose 5 – Pay Merchant</li>
                        <li>Choose 2 – Pay Masterpass QR Merchant</li>
                        <li>Enter 8-digit Merchant number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter cost <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive a confirmation SMS</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {{--class="show-grid-block"--}}
                <div class="pay_description">
                    {{--mpesaa--}}
                    <div><img src="{{ url("img/nextbyte/payment/mpesa.png") }}" height="96" width="128"></div>
                    <ol style="list-style-type: disc;">
                        <li><strong>Dial *150*00#</strong></li>
                        <li>Choose 4 - Lipa by M-Pesa</li>
                        <li>Choose 4 - Enter business number</li>
                        <li>Enter 123123 (As Selcom Pay/Masterpass business number)</li>
                        <li>Enter reference number (Pay Number) : <strong>{{ $race_registration->payment_token }}</strong></li>
                        <li>Enter cost <strong>{{ round($race_registration->cost, 0) }}</strong></li>
                        <li>Enter PIN</li>
                        <li>You will receive confirmation SMS</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-3">

        <div><strong>ALREADY PAID?</strong></div>
{{--        <a href="{{ route("payment.check_payment", $race_registration->uuid) }}" class="btn btn-primary btn-sm ml-3"><i class="icon fa fa-refresh">&nbsp;Re-Check Status</i></a>--}}
        <hr/>

        @if (!is_null($race_registration->qr))
            <div><strong>PAY BY MASTERPASS QR</strong></div>
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($race_registration->qr)) !!}" height="160" width="160">
            <hr/>
        @endif

        <div><strong>INITIATE PAYMENT DIRECT ON YOUR PHONE WITH NUMBER {{ str_replace("+", "", $race_registration->phone_number) }}</strong></div>
{{--        <a href="{{ route("payment.wallet", $race_registration->uuid) }}" class="btn btn-primary btn-sm ml-3"><i class="icon fa fa-mobile-phone">&nbsp;Pay</i></a>--}}
        <div class="help-block">Before clicking Pay, hold your phone and wait for further instruction on your phone screen. This method is currently available via <strong>Tigo</strong> and <strong>Airtel</strong> only</div>
        <hr/>

    </div>
</div>
@endif
