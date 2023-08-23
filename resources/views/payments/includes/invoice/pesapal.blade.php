<hr/>

    <div class="text-right mr-4">
        {!! Form::open(['route' => ['payment.payment', $invoice->uuid], 'class' => '','id' =>'payment_aggregator', 'novalidate','enctype'=>'multipart/form-data']) !!}
            @if(!$invoice->ispaid)
                <div class="element-form">
                    <div class="form-group pull-center">
                        {!! link_to_route('payment.history',trans('buttons.general.back'),[],['id'=> 'cancel', 'class' => 'btn btn-primary btn-sm cancel_button', ]) !!}
                        {!! Form::button(trans('label.payment.continue_to_pay'), ['class' => 'btn btn-primary btn-sm', 'type'=>'submit', 'style' => 'border-radius: 5px;']) !!}
                    </div>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
