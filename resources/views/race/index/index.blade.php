@extends('cms.layouts.main', ['title' => __('label.cms.client.client_lists') , 'header' => __('label.cms.client.client_lists')])

@include('includes.components.datatable_assets')

@push('after-styles')
    <style>
    </style>
@endpush

@section('content')


    @include('race.index.includes.registration_head_sumary')

    <section class="card mb-4">
        {{--Start: Datatable--}}
        <div class="card-body">

            @include('race.index.includes.individual_registration_dt')


            @include('race.index.includes.group_registration_dt')

        </div>
    </section>
@endsection

@push('after-scripts')

    <script>
        $(function () {


            $("#individual").on("click", function() {
                $("#individual_registrations").show()
                $("#group_registrations").hide()
            })

            $("#group").on("click", function() {

                $("#group_registrations").show()
                $("#individual_registrations").hide()
            })



        })
    </script>
@endpush
