@extends('layouts.main', ['title' => 'Home', 'header' => 'Home' ])


@push('after-styles')
    <style>

    </style>
@endpush

@section('content')


    <div class="container py-2">

        <div class="row">
            <div class="col colorder-1 order-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="featured-box featured-box-dark text-left mt-3 mt-lg-4" style="">
                            <div class="box-content">
                                <h4 class="color-primary font-weight-semibold text-4 text-uppercase mb-3">RACE SUMMARY</h4>
                                <table class="cart-totals">
                                    <tbody>
                                    <tr class="cart-subtotal">
                                        <th>
                                            <strong class="text-dark">Personal information</strong>
                                        </th>
                                    </tr>
                                    <tr class="shipping">
                                        <th>
                                            Full name
                                        </th>
                                        <td>
                                            {{$race_registration->first_name}} {{$race_registration->last_name}}
                                        </td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>
                                            Gender
                                        </th>
                                        <td>
                                            {{code_value()->nameWithNoLang($race_registration->gender_cv_id) }}
                                        </td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>
                                            Contacts
                                        </th>
                                        <td>
                                            {{$race_registration->email }}  ||  {{$race_registration->phone_number }}
                                        </td>
                                    </tr>

                                    <tr class="shipping">
                                        <th>
                                            Address
                                        </th>
                                        <td>
                                            {{$race_registration->nationality }} {{$race_registration->address }}
                                        </td>
                                    </tr>



                                    <tr class="total">
                                        <th>
                                            <strong class="text-dark">Race information</strong>
                                        </th>
                                    </tr>

                                    @if($race_registration->race_type_cv_id == 8)
                                    @include('race.includes.personal_reg_summary')
                                    @else
                                    @include('race.includes.group_reg_summary')
                                    @endif
                                    </tbody>
                                </table>



                                <div class="card mt-4">
                                    <div class="card-body">


                                        @include('payments.includes.invoice.selcom')


                                        @include('payments.includes.other_payments')

                                    </div>
                                </div>



                                <div class="form-row ">
                                    <div class="form-group col-md-12 mb-5 mt-2">
                                        <input type="submit" id="contactFormSubmit" value="Complete payment"
                                               class="btn btn-primary btn-modern pull-right" data-loading-text="Loading...">
                                        <a type="submit" id="contactFormSubmit" href="{{route('race.registration')}}"
                                           class="btn btn-default btn-modern pull-right" data-loading-text="Loading...">Cancel</a>
                                    </div>
                                </div>
                            </div>





                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>



    @include('race.includes.terms_modal')


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
