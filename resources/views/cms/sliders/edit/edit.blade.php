@extends('cms.layouts.main', ['title' => __("label.service.create"), 'header' => __("label.service.create")])

@push('after-styles')
    <style>
        [data-toggle="collapse"] .fa:before {
            content: "\f139";
        }

        [data-toggle="collapse"].collapsed .fa:before {
            content: "\f13a";
        }

    </style>
@endpush
@section("content")
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => ['cms.slider.update',$slider->uuid],'method'=>'put', 'autocomplete' => 'off',  'id' =>'store_service', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}
            {!! Form::hidden('action_type', 2, []) !!}

            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
{{--                    <button type="button" class="mb-1 mt-1 mr-1 btn btn-primary btn-xs pull-right"><i class="fas fa-paper-plane"></i> {{trans('label.service.publish')}}</button>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    @include('cms.sliders.edit.includes.slider_info')

                </div>


            </div>

            <hr>
            <div class="row">
                <div class="col-md-12 ">
                    {!! link_to_route('cms.slider.index',trans('label.cancel'),[],['id'=> 'cancel', 'class' => 'btn btn-primary  cancel_button', ]) !!}
                    {!! Form::button(trans('label.save'), ['class' => 'btn btn-primary ','id' => 'save_btn', 'type'=>'submit']) !!}
                </div>

            </div>

            {{ Form::close() }}

        </div>
    </div>

@endsection


@push('after-scripts')
    {!! Html::script(url('cms/vendor/ckeditor5/ckeditor.js')) !!}

    <script>
        $(function () {

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                })
                .then( editor => {
                    theEditor = editor;
                } )
                .catch( error => {
                    console.error( error );
                } );

            //for the editor
            // ClassicEditor.create( document.querySelector( '#editor' ) );

            //for select 2
            $(".select2").select2();


            $(document).ready(function() {
                $(document).ready(function() {

                    var systemSelect = $(".isschedule option:selected").val();
                    if (systemSelect ==1 ) {

                        activate_schedule_div()
                    } else {
                        hide_schedule_div()
                    }

                    $("select.isschedule").change(function () {
                        var selectedOption = $(this).children("option:selected").val();
                        if(selectedOption == 1)
                        {
                            activate_schedule_div()
                        }else
                        {
                            hide_schedule_div()

                        }

                    });
                });
                //show contact person div
                function activate_schedule_div()
                {
                    $("#" + 'schedule_div').show();

                }

                //hide contact person div
                function hide_schedule_div() {
                    $("#" + 'schedule_div').hide();

                }
            });



            //save service before publish
            {{--$(document).on('click','#save_btn',function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var title = $('#title').val();--}}
            {{--    // var description = $('#editor').val();--}}
            {{--    var description = theEditor.getData();--}}
            {{--    var category  = $('#service_category').val();--}}
            {{--    var publish_date  = $('#publish_date').val();--}}
            {{--    var publish_time  = $('#publish_time').val();--}}
            {{--    var data = {--}}
            {{--        'title' : title,--}}
            {{--        'content' :description,--}}
            {{--        'category' : category,--}}
            {{--        'publish_date' : publish_date,--}}
            {{--        'publish_time' : publish_time,--}}

            {{--    };--}}
            {{--    console.log(data)--}}
            {{--    var formData = new FormData(document.getElementById( 'store_service'));--}}
            {{--    var url = '{{route('cms.service.store')}}';--}}
            {{--    $.ajax({--}}
            {{--        type: 'post',--}}
            {{--        url: url,--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        },--}}
            {{--        data: data,--}}
            {{--        cache: false,--}}

            {{--        success: function (response) {--}}
            {{--            console.log(response);--}}
            {{--            if (response) {--}}
            {{--                // new PNotify({--}}
            {{--                //     title: 'Document is successfully attached in',--}}
            {{--                //     type: 'info',--}}
            {{--                // });--}}
            {{--            }--}}
            {{--        },--}}
            {{--    }).done(--}}

            {{--    );--}}
            {{--})--}}


        })
    </script>

@endpush
