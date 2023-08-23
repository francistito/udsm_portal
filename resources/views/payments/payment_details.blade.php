
@extends('layouts.main')


@section('content')
    <section class="section section-concept section-no-border section-dark section-angled section-angled-reverse pt-5 m-0" id="section-concept" style="background-image: url(img/landing/header_bg.jpg); background-size: cover; background-position: center; animation-duration: 750ms; animation-delay: 300ms; animation-fill-mode: forwards;">
        <div class="container pt-5 mt-5">
            <div class="row align-items-center pt-3">

            </div>
        </div>
    </section>
    <div class="container">
        {!! Form::open(['route' => ['payment.nextpay.ussd', $invoice->uuid], 'class' => 'needs-validation',]) !!}

        {{ Form::hidden('product_id', $product->id, ['id' =>'product']) }}
        {{ Form::hidden('user_id', $user->id, ['id' =>'user']) }}
        {{ Form::hidden('action_type',1, ['id' =>'user']) }}


        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Contact details</h4>
                            </div>
                            <div class="col-md-4">
                                @if($invoice->status == 0)

                                <a class="btn  btn-danger font-weight-semibold px-4 ms-3" href="{{route('payment.cancel_invoice',$invoice->uuid)}}">
                                    {{trans('label.cancel_invoice')}}</a>

                                @endif
                            </div>
                        </div>
                        <hr>
                        <p>{{$user->firstname  .' '. $user->lastname}}</p>
                        <p>{{$user->phone_number}}</p>
                        <p>{{$user->email}}</p>



                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="col-form-label form-control-label line-height-9 pt-2 text-2 "><strong>Domain Name</strong></label>
                                  <small>Enter your domain if you have</small>
{{--                                    <input class="form-control text-3 h-auto py-2" type="text" name="domain_name" value=""  aria-invalid="false">--}}

                                    {{ Form::text('domain_name',null,['class'=>'form-control text-3 h-auto py-2', 'id' => 'domain_name','placeholder' => '', 'autocomplete' => 'off']) }}
                                    {!!  $errors->first('domain_name', '<span class="badge badge-danger">:message</span>') !!}
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class=" col-form-label form-control-label line-height-9 pt-2 text-2 "><strong>Company profile</strong></label>
                                    <small>you can submit later </small>
{{--                                    <input class="form-control text-3 h-auto py-2" type="file" name="company_profile" value=""  aria-invalid="false">--}}

                                    {{ Form::file('company_profile',null,['class'=>'form-control text-3 h-auto py-2', 'id' => 'domain_name','placeholder' => '', 'autocomplete' => 'off']) }}
                                    {!!  $errors->first('company_profile', '<span class="badge badge-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12">
                                <h4>Payment details</h4>
                                <hr>

                                @if($invoice->status == 0)
                                    <img src="{{url('img/lipa.png')}}" height="100" style="margin-left: 200px;margin-bottom: 20px">
                                    <br>



                                                                    @include('payments.includes.invoice.nextpay')


                                @include('payments.includes.other_payments')

                                @include('payments.includes.reference_number_input')

                                    <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase bg-color-hover-primary border-color-hover-primary border-radius-0 text-3 py-3" id="save_brand">{{trans('Complete payment')}} <i class="fas fa-arrow-right ms-2"></i></button>
                                    {{ Form::close() }}
                                @elseif($invoice->status == 1)
                                    <div class="row">
                                        <div class="col-md-10 ">
                                            <div>
                                                <p style="color: #217518">YOUR PAYMENT IS SUCCESSFULLY SUBMITED YOU WILL BE CONTACT FOR FATHER STEP</p>
                                                <p class="" style="font-weight: bold" >for any enquiries please call +255745 680 053</p>
                                            </div>

                                            <a href="{{route('payment.redirect_to_cms_after_payment',$invoice->uuid)}}" class="btn btn-dark btn-modern w-100 text-uppercase bg-color-hover-primary border-color-hover-primary border-radius-0 text-3 py-3" id="save_brand">{{trans('label.configure_your_website')}} <i class="fas fa-arrow-right ms-2"></i></a>


                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>





                    </div>



                </div>

            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-body">
                        <table class="shop_table cart-totals mb-4">
                            <tbody>
                            <strong class="d-block text-color-dark mb-2">Order Summary</strong>
                            <tr class="shipping">
                                <td colspan="2">
                                    <div class="d-flex flex-column">
                                        <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method1">
                                            {{$product->name}}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-end">
                                    {{number_2_format($product->getProductPackagePrice())}}
                                </td>
                            </tr>

                            <tr class="shipping">
                                <td colspan="2">
                                    <div class="d-flex flex-column">
                                        <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method1">
                                            {{trans('label.hosting_charges')}} <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="For the freemium template ,you should pay the hosting service from nexthost minimum one year" style="color:#000000"></i>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-end">
                                    {{number_2_format(sysdef()->data('HOSTPRC'))}}
                                </td>
                            </tr>
                            @foreach($package_services as $package)

                                <tr class="shipping">
                                    <td colspan="2">
                                        <div class="d-flex flex-column">
                                            <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method1">
                                                {{$package->name}}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{number_2_format($package->price)}}
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="total">
                                <td colspan="2">
                                    <strong class="text-color-dark text-3-5">Total</strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-color-dark"><span class="amount text-color-dark text-5">{{number_2_format($total_amount)}}</span></strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection


@push('after-scripts')

    <script>

        $(function (){
            $("input[name='channel']").change(function() {
                let channel = $(this).data('channel');          // Do something interesting here

                $("div.pay_description").hide();
                $("#" + channel).show();
            });
        })

    </script>

@endpush

