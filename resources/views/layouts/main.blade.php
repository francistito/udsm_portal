<!DOCTYPE html>
<!-- devcode: !production -->
<html><!-- endcode --><!-- devcode: production -->

<!-- Mirrored from preview.oklerthemes.com/porto/8.0.0/elements-forms-advanced-contact.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Jun 2020 15:55:59 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/><!-- /Added by HTTrack -->
<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Forms | Porto - Responsive HTML5 Template</title>

    <meta name="keywords" content="HTML5 Template"/>
    <meta name="description" content="Porto - Responsive HTML5 Template">
    <meta name="author" content="okler.net">



    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{url('vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('vendor/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{url('vendor/animate/animate.min.css')}}">
    <link rel="stylesheet" href="{{url('vendor/simple-line-icons/css/simple-line-icons.min.css')}}">
    <link rel="stylesheet" href="{{url('vendor/magnific-popup/magnific-popup.min.css')}}">
{{ Html::style(url('cms/vendor/select2/css/select2.min.css')) }}

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{url('css/theme.css')}}">
    <link rel="stylesheet" href="{{url('css/theme-elements.css')}}">


    <!-- Demo CSS -->
@stack('after-styles')


    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{url('css/skins/default.css')}}">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{url('css/custom.css')}}">

    <!-- Head Libs -->
    <script src="{{url('vendor/modernizr/modernizr.min.js')}}"></script>

</head>
<body>

<div class="body" style="background-image:  url('{{ asset('img/background.jpg')}}'); " >

    @include('includes.header')

    <div role="main" class="main mt-4" >

{{--        @include('includes.components.sidebar')--}}
        @include("includes.components.messages")

        <section role="main"class="content-body">
            @yield('content')
        </section>


    </div>

    @include('includes.footer')
</div>

<!-- Vendor -->
<script src="{{url('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{url('vendor/jquery.appear/jquery.appear.min.js')}}"></script>
<script src="{{url('vendor/jquery.easing/jquery.easing.min.js')}}"></script>
<script src="{{url('vendor/jquery.cookie/jquery.cookie.min.js')}}"></script>
<script src="{{url('vendor/popper/umd/popper.min.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('vendor/common/common.min.js')}}"></script>
<script src="{{url('vendor/jquery.validation/jquery.validate.min.js')}}"></script>
<script src="{{url('vendor/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
{{ Html::script(url('cms/vendor/select2/js/select2.min.js')) }}

@stack('after-scripts')

<!-- Theme Base, Components and Settings -->
<script src="{{url('js/theme.js')}}"></script>

<!-- Current Page Vendor and Views -->
<script src="{{url('js/examples/examples.forms.js')}}"></script>


<!-- Theme Initialization Files -->
<script src="{{url('js/theme.init.js')}}"></script>

<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'http://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-42715764-5', 'auto');
    ga('send', 'pageview');
</script>

</body>

<!-- Mirrored from preview.oklerthemes.com/porto/8.0.0/elements-forms-advanced-contact.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Jun 2020 15:55:59 GMT -->
</html>
