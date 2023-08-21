@extends('cms.layouts.main', ['title' => __("label.blog.create"), 'header' => __("label.blog.create")])

@include('includes.validate_assets')
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
            {!! Form::open(['route' => ['cms.slider.store'],'method'=>'post', 'autocomplete' => 'off',  'id' =>'store_blog', 'class' => 'form-horizontal needs-validation', 'novalidate','enctype'=>"multipart/form-data"]) !!}
            {!! Form::hidden('action_type', 1, []) !!}

            <div class="row">
                <div class="col-md-8">
                    @include('cms.sliders.create.includes.slider_info')

                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12 ">
                    {!! link_to_route('cms.slider.index',trans('label.cancel'),[],['id'=> 'cancel', 'class' => 'btn btn-primary cancel_button', ]) !!}
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

            ClassicEditor.create( document.querySelector( '#editor' ) );

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
