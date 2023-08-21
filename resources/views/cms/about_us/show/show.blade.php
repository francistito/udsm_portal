@extends('cms.layouts.main', ['title' => __('About us') , 'header' => __('About ud ')])

@include('includes.sweetalert_assets')
@include('includes.datatable_assets')

@push('after-styles')
    {{ Html::style(url('vendor/dropzone/dropzone.css')) }}
    {{ Html::style(url('vendor/dropzone/basic.css')) }}
    <style>
    </style>
@endpush

@section('content')


    <!-- start: page -->

    <div class="row">
        <div class="col-md-12">

            <div class="tabs tabs-dark">

                <div class="tab-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right mb-2">
                                    <a href="{{ route('cms.about_us.edit',$about_us->id) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> {{ __('Edit') }}</a>
                                </div>

                            </div>

                        </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" id="">
                                        <tbody>
                                        <tr>
                                            <th width="170px"> {{trans('Mission')}} : </th>
                                            <td>{!! $about_us->mission !!}</td>
                                        </tr>
                                        <tr>
                                            <th width="170px"> {{trans('Vision')}} : </th>
                                            <td>{!! $about_us->vision !!}</td>
                                        </tr>


                                        <tr>
                                            <th width="170px"> {{trans('Our value')}} : </th>
                                            <td>{!! $about_us->our_goal !!}</td>
                                        </tr>

                                        <tr>
                                            <th width="170px"> {{trans('About us intro')}} : </th>
                                            <td>{!! $about_us->about_us_intro !!}</td>
                                        </tr>
                                        <tr>
                                            <th width="170px"> {{trans('About us')}} : </th>
                                            <td id="description">{!! $about_us->about_us !!}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>



                        </div>

                        <div class="col-md-3">

                        </div>
                    </div>






                </div>
            </div>
        </div>


    </div>
    <!-- end: page -->

@endsection

@push('after-scripts')
    {{ Html::script(url('vendor/jquery-expander/jquery.expander.js')) }}
    {!! Html::script(url('assets/nextbyte/plugins/dropzone/dropzone.js')) !!}

    <script>

        $(function () {
            $('#description').expander({
                slicePoint: 300,
                widow: 2,
                expandEffect: 'show',
                userCollapseText: 'Read Less',
                expandText: 'Read More'
            });
            //delete blog
            $(document).on('click','.delete_blog',function (e) {
                e.preventDefault();
                var blog_id = $(this).attr('id');
                swal({
                    title: "Are you sure you want to delete?",
//                text: "You $("#url").data("case") a contact from a group!",
                    icon: "warning",
                    buttons: [
                        'No, cancel!',
                        'Yes, I am sure!'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm)  {
                    if (isConfirm) {
                        var url = '{{route('cms.blog.delete')}}';
                        var data = {'blog_id':blog_id};
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: url,
                            type: "post",
                            dataType: 'JSON',
                            data: data,
                            success: function (response) {
                                if (response){
                                    $('#blog'+blog_id).remove();
                                    window.location.href = '{{route('cms.blog.index')}}'
                                    new PNotify({
                                        title: 'Blog is successfully deleted ',
                                        type: 'info',
                                    });
                                }
                            }
                        }).done(

                        )

                    } else {
//                    swal("Cancelled", "You have cancel Action", "error");

                    }
                    $(".swal-overlay").remove();
                });
            })


            $(document).ready(function() {

            });




            $(document).on('click', '.modal-confirm', function (e) {
                e.preventDefault();
                $.magnificPopup.close();

                new PNotify({
                    title: 'Success!',
                    text: 'Modal Confirm Message.',
                    type: 'success'
                });
            });
        });




    </script>
@endpush
