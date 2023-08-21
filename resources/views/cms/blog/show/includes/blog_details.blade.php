@extends('layouts.main', ['title' => __("label.about_us"), 'header' => __("label.about_us")])

@push('after-styles')
    <style>
        .about_features_card{
            background: white;
            border-radius: 6px;
            height: 450px;
            max-height: 450px;
            overflow: hidden;
            padding: 2rem;
            transition: max-height 500ms ease-in-out;
        }
        .about_features_card:hover{
            max-height: 600px;
        }
    </style>
@endpush

@section('content')
    <div class="rs-breadcrumbs bg-9" style=" background-image: url('{{ asset('assets2/images/bg/footer-bg3.jpg')}}');">
        <div class="container">
            <div class="content-part text-center">
                <h1 class="breadcrumbs-title white-color mb-0">{{$blog->title}}</h1>
            </div>
        </div>
    </div>
    <div class="rs-blog inner single pt-100 pb-100 md-pt-80 md-pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-part">
                        <div class="blog-img">
                            <a href="#"><img src="{{$blog->getImageUrlAttribute($blog)}}" alt=""></a>
                        </div>
                        <div class="article-content shadow mb-60">
                            <ul class="blog-meta mb-22">
                                <li><i class="fa fa-calendar-check-o"></i> {{short_date_format($blog->created_at)}}</li>
{{--                                <li><i class="fa fa-user-o"></i> {{$blog->}}</li>--}}
                            </ul>

                            <p class="desc">{!! $blog->content !!}</p>

                        </div>

                    </div>
                </div>

                <div class="col-lg-4 md-mb-50 pl-35 lg-pl-15 md-order-first">
                    <div id="sticky-sidebar" class="blog-sidebar" style="position: static; top: -230px;">

                        <div class="sidebar-popular-post sidebar-grid shadow mb-50">
                            <div class="sidebar-title">
                                <h3 class="title mb-20">Recent Post</h3>
                            </div>

                            @foreach(\App\Models\Cms\Blog::all() as $blog)

                            <div class="single-post mb-20">
                                <div class="post-image">
                                    <a href="{{$blog->getImageUrlAttribute($blog)}}"><img src="{{$blog->getImageUrlAttribute($blog)}}" alt="post image"></a>

                                    <div class="post-desc">

                                        <div class="post-title">
                                        <h5 class="margin-0"><a href="{{route('cms.blog.view_blog',$blog->id)}}">{{$blog->title}} </a></h5>
                                    </div>
                                    <ul>
                                        <li><i class="fa fa-calendar"></i> 28 June, 2019</li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div id="sticky-end"></div>
        </div>
    </div>



@endsection



@push('after-scripts')
    <script>

    </script>
@endpush
