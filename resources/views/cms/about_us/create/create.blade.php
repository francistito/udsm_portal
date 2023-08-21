@extends('cms.layouts.main', ['title' => __("label.blog.create"), 'header' => __("label.blog.create")])

@push('after-styles')
    {{ Html::style(url('vendor/dropzone/dropzone.css')) }}
    {{ Html::style(url('vendor/dropzone/basic.css')) }}


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
            {!! Form::open(['route' => ['cms.about_us.store'],'method'=>'post', 'autocomplete' => 'off',  'id' =>'store_blog', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}
            {!! Form::hidden('action_type', 1, []) !!}

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
                        <div class="col-6">
                            {!! Form::label('phone_number', __("Phone number"), ['class' => '']) !!}
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('phone_number', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                        <div class="col-6">
                            {!! Form::label('email', __("Email"), ['class' => '']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('email', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            {!! Form::label('address', __("Address"), ['class' => '']) !!}
                            {!! Form::text('address', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('address', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                        <div class="col-6">
                            {!! Form::label('instagram_link', __("Instagram"), ['class' => '']) !!}
                            {!! Form::text('instagram_link', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('instagram_link', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            {!! Form::label('twitter_link', __("Twitter"), ['class' => '']) !!}
                            {!! Form::text('twitter_link', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('twitter_link', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                        <div class="col-6">
                            {!! Form::label('facebook_link', __("Facebook"), ['class' => '']) !!}
                            {!! Form::text('facebook_link', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('facebook_link', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            {!! Form::label('youtube_link', __("Youtube"), ['class' => '']) !!}
                            {!! Form::text('youtube_link', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('youtube_link', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                        <div class="col-6">
                            {!! Form::label('linkedin_link', __("Linkedin"), ['class' => '']) !!}
                            {!! Form::text('linkedin_link', null, ['class' => 'form-control', 'autocomplete' => 'off', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('linkedin_link', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('mission', __("Mission"), ['class' => '']) !!}
                            {!! Form::textarea('mission', null, ['class' => 'form-control', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor1', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('mission', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('vision', __("Vision"), ['class' => '']) !!}
                            {!! Form::textarea('vision', null, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor2', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('vision', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('our_value', __("Our goal"), ['class' => '']) !!}
                            {!! Form::textarea('our_value', null, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor3', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('our_value', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('about_us_intro', __("About us Introduction (We are) "), ['class' => '']) !!}
                            {!! Form::textarea('about_us_intro', null, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor4', 'aria-describedby' => 'contentHelp', ]) !!}

                            {!! $errors->first('about_us_intro', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row mt-2" >
                        <div class="col-lg-12">
                            {!! Form::label('about_us', __("About Us"), ['class' => '']) !!}

                            {!! Form::textarea('about_us', null, ['class' => 'form-control editor', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor5', 'aria-describedby' => 'contentHelp', ]) !!}
                            {!! $errors->first('about_us', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>


                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! Form::button(trans('label.save'), ['class' => 'btn btn-primary btn-xs','id' => 'save_btn', 'type'=>'submit']) !!}
                </div>

            </div>

            {{ Form::close() }}

        </div>
    </div>

@endsection


@push('after-scripts')
    {!! Html::script(url('cms/vendor/ckeditor5/ckeditor.js')) !!}
    {!! Html::script(url('assets/nextbyte/plugins/dropzone/dropzone.js')) !!}


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



            //save blog before publish
            // $(document).on('click','#save_btn',function (e) {
            //     e.preventDefault();
            //     var task_title = $('#title').val();
            //     // var description = $('#editor').val();
            //     var content = theEditor.getData();
            //     var category  = $('#blog_category_id').val();
            //     var publish_date  = $('#publish_date').val();
            //     var publish_time  = $('#publish_time').val();
            //     var file  = $('#file').val();
            //     if ( task_title.length == 0 || content.length == 0) {
            //         $('#title_errors').empty();
            //         $('#content_errors').empty();
            //         if (task_title.length == 0)
            //         {
            //             $('#title_errors').append(`<p id="client_errors" style="color: red">Title required</p>`);
            //         }
            //
            //         if (content.length == 0)
            //         {
            //             $('#content_errors').append(`<p id="client_errors" style="color: red">Content required</p>`);
            //         }
            //
            //         return false;
            //     }
            //     $('#store_blog').submit();
            //
            //
            // })
            $(document).ready(function() {
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

    })
    </script>

@endpush
