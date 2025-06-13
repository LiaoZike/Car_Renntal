<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>管理後台</title>
    <!-- loader-->
    {{--    <link href="{{asset('dist/css/pace.min.css')}}" rel="stylesheet"/>--}}
    {{--    <script src="{{asset('dist/js/pace.min.js')}}"></script>--}}
    <!--favicon-->
    <link rel="icon" href="{{asset('media/favicon.ico')}}'" type="image/x-icon">
    <!-- simplebar CSS-->
    <link href="{{asset('dist/css/simplebar.css')}}" rel="stylesheet"/>
    <!-- Bootstrap core CSS-->

    {{--    <link href="{{asset('dist/css/bootstrap.min.css')}}" rel="stylesheet"/>--}}

    <!-- animate CSS-->
    <link href="{{asset('dist/css/animate.min.css')}}" rel="stylesheet"/>
    <!-- Icons CSS-->
    <link href="{{asset('dist/css/iconic.css')}}" rel="stylesheet"/>
    <!-- Sidebar CSS-->
    <link href="{{asset('dist/css/sidebar-menu.css')}}" rel="stylesheet"/>
    <!-- Custom Style-->
    <link href="{{asset('customize/admin/master.css')}}" rel="stylesheet"/>
    @yield('source_css')
    <link href="{{asset('dist/css/bootstrap5_1_3.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('template_dist/css/sweetalert2.min.css')}}">

</head>
<body class="bg-theme bg-theme1">
@yield('main_css')
<div id="wrapper">
    <!--Start sidebar-wrapper-->
    @include('admin.blade.sidebar')
    <!--End topbar header-->

    <!--Start topbar header-->
    @include('admin.blade.header')
    <!--End sidebar-wrapper-->

    <div class="clearfix"></div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!--Start Content-->
            @yield('main_section')
            @yield('second_section')
            <!--End Content-->
        </div>
    </div>
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--Start footer-->
    @include('admin.blade.footer')
    <!--End footer-->
</div><!--End wrapper-->

<!-- Bootstrap core JavaScript-->
<script src="{{asset('dist/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
<!-- simplebar js -->
<script src="{{asset('dist/js/simplebar.js')}}"></script>
<!-- sidebar-menu js -->
<script src="{{asset('dist/js/sidebar-menu.js')}}"></script>
<!-- Custom scripts -->
<script src="{{asset('customize/admin/master.js')}}"></script>
<script src="{{asset('template_dist/js/sweetalert2.js')}}"></script>
@yield('source_js')
@yield('main_js')
<script>
@if(session('web_status'))
    setTimeout(function (){
    showToast("{{session('web_status_description')}}", "{{session('web_status')}}",2000);
        <?php session()->forget('web_status_description');session()->forget('web_status'); // 清除 session?>
    },200)
@endif
</script>
</body>
</html>
