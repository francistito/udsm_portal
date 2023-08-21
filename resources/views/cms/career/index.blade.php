@extends('cms.layouts.main', ['title' => __("label.blog.blog"), 'header' => __("label.blog.blog")])

@include('includes.datatable_assets')
@push('after-styles')
    <style>

    </style>
@endpush
@section("content")

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        @include('cms.career.includes.careers_datatable')
                    </div>
                </div>

            </div>
        </div>
    </div>




@endsection

@push('after-scripts')
    {!! Html::script(url('vendor/sweetalert/sweetalert.min.js')) !!}

    {!! Html::script(url('cms/vendor/ckeditor5/ckeditor.js')) !!}
    <script>


    </script>

@endpush
