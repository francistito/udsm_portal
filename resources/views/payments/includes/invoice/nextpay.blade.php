@if(!$race_registration->ispaid)
<div class="row">
{{--@if(!$invoice->payment_status)--}}
        <div class="col-md-12">
{{--            {!! Form::open(['route' => ['payment.nextpay.ussd', $invoice->uuid], 'class' => 'needs-validation',]) !!}--}}
            <div class="row">
                <div class="col-md-10 ">
                    <div>
                        <p style="color: #0a6ad2">INITIATE PAYMENT DIRECT ON YOUR PHONE WITH NUMBER {{--{{ str_replace("+", "", $user->phone_number) }}--}}</p>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-phone text-warning"></i></div>
                            </div>
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="phone_number" name="phone_number" placeholder="@lang('Phone number')" required>
                            {{--                        <input type="hidden" name="customer" value="{!! $customer->id !!}">--}}
                            @if ($errors->has('phone_number'))
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: red">{{ $errors->first('phone_number') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

{{--    @endif--}}

{{--    <div class="col-md-6">--}}
{{--        <div><strong>ALREADY PAID?</strong></div>--}}
{{--        <a href="{{ route("payment.check_payment", $invoice->uuid) }}" class="btn btn-primary btn-sm ml-3"><i class="icon fa fa-refresh">&nbsp;Re-Check Status</i></a>--}}
{{--    </div>--}}
</div>
@endif

@push('after-scripts')

    {!! Html::script(url('vendor/jquery-maskedinput/jquery.maskedinput.js')) !!}
    <script>
        $(function () {
            $.mask.definitions['~'] = "[+-]";
            $('#phone_number').mask("0999999999");
        });
    </script>

@endpush
