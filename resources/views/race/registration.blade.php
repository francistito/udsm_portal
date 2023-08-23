@extends('layouts.main', ['title' => 'Home', 'header' => 'Home' ])


@push('after-styles')
    <style>

    </style>
@endpush

@section('content')


    <div class="container py-2">

        <div class="row">
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="card">
                    <div class="card-body">

                            {!! Form::open(['route' => ['race.store'],'method'=>'post', 'autocomplete' => 'off',  'id' =>'store_registration', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}

                            <input type="hidden" value="true" name="emailSent" id="emailSent">

                            <div class="card">
                                <div class="card-body">
                                    <h5>Personal information</h5>
                                    <hr>
                                    @include('race.includes.personal_information')
                                </div>
                            </div>



                            <div class="card mt-4">
                                <div class="card-body ">
                                    <h5 >Race information</h5>
                                    <hr>
                                    @include('race.includes.race_information')
                                </div>
                            </div>


                        <div class="form-row mt-2">
                            <div class="col">
                                <div class="custom-control custom-checkbox pb-3">
                                    <input type="checkbox" name="terms" id="terms" required>
                                    {!! $errors->first('terms', '<span class="badge badge-danger">:message</span>') !!}

                                    <a> <label class=""  data-toggle="modal" data-target="#noAnimModal">I agree with Terms and conditions</label></a>

                                </div>
                            </div>
                        </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-5">
                                    <input type="submit" id="contactFormSubmit" value="Submit"
                                           class="btn btn-primary btn-modern pull-right" data-loading-text="Loading...">
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>


    </div>



    @include('race.includes.terms_modal')


@endsection
@push('after-scripts')
    <script>
        $(function() {
            $('select').select2({});
            jQuery('.select2-container').css('width','100%');

            $('body').on('submit', 'form[name=create]', function(e) {
                e.preventDefault();
                alert(33)
                /*Codes Here*/
                pleaseWaitSubmitButton("allowance_type","label","{{ trans('label.please_wait') }}",1)

                this.submit();

            });






            $(document).ready(function() {
                $("#race_type").change(function () {
                    var selectedOption = $(this).children("option:selected").val();
                    if(selectedOption == 9)
                    {
                        activate_group_type()
                    }else
                    {
                        activate_individual_type();

                    }

                });
            });

            function activate_group_type()
            {
                $("#" + 'group').show();
                $("#" + 'individual').hide();

                $("." + 'individual_input').prop("disabled", true);

                $("." + 'group_input').prop("disabled", false);
                jQuery('.select2-container').css('width','100%');

            }
            function activate_individual_type() {
                $("#" + 'group').hide();
                $("#" + 'individual').show();
                $("." + 'group_input').prop("disabled", true);
                $("." + 'individual_input').prop("disabled", false);
            }

            // window.onload = function () {
            //     var divSelect = $( "#is_race_type option:selected" ).val();
            //
            //     if (divSelect ==1 ) {
            //         activate_race_type()
            //     } else {
            //         hide_race_type_div();
            //     }
            // };
        });
    </script>
@endpush
