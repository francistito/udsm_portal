@extends('cms.layouts.main', ['title' => __("label.blog.create"), 'header' => __("label.blog.create")])

{{--@include('includes.datetimepicker')--}}
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
            {!! Form::open(['route' => ['cms.about_us.update',$about_us->id],'method'=>'put', 'autocomplete' => 'off',  'id' =>'store_blog', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}
            {!! Form::hidden('action_type', 2, []) !!}

            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    {{--                    <button type="button" class="mb-1 mt-1 mr-1 btn btn-primary btn-xs pull-right"><i class="fas fa-paper-plane"></i> {{trans('label.blog.publish')}}</button>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('mission', __("Mission"), ['class' => '']) !!}
                            {!! Form::textarea('mission', $about_us->mission, ['class' => 'form-control', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor1', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('mission', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('vision', __("Vision"), ['class' => '']) !!}
                            {!! Form::textarea('vision', $about_us->vision, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor2', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('vision', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('our_value', __("Our goal"), ['class' => '']) !!}
                            {!! Form::textarea('our_value', $about_us->our_goal, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor3', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('our_value', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('about_us_intro', __("About us Introduction (We are) "), ['class' => '']) !!}
                            {!! Form::textarea('about_us_intro', $about_us->about_us_intro, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor4', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('about_us_intro', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row mt-2" >
                        <div class="col-lg-12">
                            {!! Form::label('about_us', __("About Us"), ['class' => '']) !!}

                            {!! Form::textarea('about_us', $about_us->about_us, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor5', 'aria-describedby' => 'contentHelp', ]) !!}
                            {!! $errors->first('about_us', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>


                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! Form::button(trans('Save'), ['class' => 'btn btn-primary btn-xs','id' => 'save_btn', 'type'=>'submit']) !!}
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

            ClassicEditor.create( document.querySelector( '#editor1' ) );
            ClassicEditor.create( document.querySelector( '#editor2' ) );
            ClassicEditor.create( document.querySelector( '#editor3' ) );
            ClassicEditor.create( document.querySelector( '#editor4' ) );
            ClassicEditor.create( document.querySelector( '#editor5' ) );

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



            //save blog before publish
            {{--$(document).on('click','#save_btn',function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var title = $('#title').val();--}}
            {{--    // var description = $('#editor').val();--}}
            {{--    var description = theEditor.getData();--}}
            {{--    var category  = $('#blog_category').val();--}}
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
            {{--    var formData = new FormData(document.getElementById( 'store_blog'));--}}
            {{--    var url = '{{route('cms.blog.store')}}';--}}
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
