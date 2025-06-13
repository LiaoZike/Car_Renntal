<!doctype html>
<html lang="zh-Hant-TW">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('template_dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/fonts/icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/fonts/flaticon/font/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('template_dist/css/style.css')}}">
    <link rel="icon" href="{{ asset('car.ico') }}" type="image/x-icon">
    @yield('source_css')
</head>
<body>
@yield('main_css')
<div class="site-wrap">
    @include('home/blade/header')
    <div id="main-section">
    @yield('main_section')
    </div>
{{--    @include('home/blade/footer')--}}
</div>
@yield('second_section')
<script src="{{asset('template_dist/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('template_dist/js/popper.min.js')}}"></script>
<script src="{{asset('template_dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('template_dist/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('template_dist/js/jquery.sticky.js')}}"></script>
<script src="{{asset('template_dist/js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('template_dist/js/jquery.animateNumber.min.js')}}"></script>
<script src="{{asset('template_dist/js/jquery.fancybox.min.js')}}"></script>
<script src="{{asset('template_dist/js/jquery.easing.1.3.js')}}"></script>
<script src="{{asset('template_dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('template_dist/js/aos.js')}}"></script>
<script src="{{asset('template_dist/js/main.js')}}"></script>
<script src="{{asset('template_dist/js/sweetalert2.js')}}"></script>
@yield('source_js')
@yield('main_js')
<script>
@if(session('web_status'))
    showToast("{{session('web_status_description')}}", "{{session('web_status')}}");
    <?php session()->forget('web_status_description');session()->forget('web_status'); // 清除 session?>
@endif
</script>
</body>

</html>
