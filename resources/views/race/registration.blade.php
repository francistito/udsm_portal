@extends('layouts.main', ['title' => 'Home', 'header' => 'Home' ])


@push('after-styles')
    <style>

    </style>
@endpush

@section('content')

    <section
        class="page-header page-header-modern page-header-background page-header-background-sm overlay overlay-color-primary overlay-show overlay-op-8 mb-5"
        style="background-image: url(img/page-header/page-header-elements.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1>Forms</h1>

                </div>
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb breadcrumb-light d-block text-center">
                        <li><a href="#">Home</a></li>
                        <li class="active">Elements</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-2">

        <div class="row">
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="card">
                    <div class="card-body">

                            {!! Form::open(['route' => ['race.store_registration'],'method'=>'post', 'autocomplete' => 'off',  'id' =>'store_registration', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}

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






                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-5">
                                    <input type="submit" id="contactFormSubmit" value="Send Message"
                                           class="btn btn-primary btn-modern pull-right" data-loading-text="Loading...">
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>


            </div>
        </div>




    </div>

@endsection
@push('after-scripts')
    <script>
        $(function() {
            $('select').select2({});

            $('body').on('submit', 'form[name=create]', function(e) {
                e.preventDefault();
                /*Codes Here*/
                pleaseWaitSubmitButton("allowance_type","label","{{ trans('label.please_wait') }}",1)

                this.submit();

            });
        });
    </script>
@endpush
