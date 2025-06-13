<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{config('admin.web_icon')}}" sizes="64x64" />

    <link href="{{asset('dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/adminlte.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('template_dist/css/sweetalert2.min.css')}}">
    <title>{{config('admin.title')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <style>
        body {
            background: url('{{asset(config('admin.web_background'))}}') no-repeat center center;
            background-size: cover;
            height: 100vh;
            position: relative;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .login-box {
            position: relative;
            z-index: 2;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 10px;
            backdrop-filter: blur(3px);
        }

        .login-logo {
            background-color: #515151;
            margin-bottom: 20px !important;
            padding: 0 0 10px 0;
            border-radius: 5px;
        }
        .login-logo span {
            color: white;
        }

        .cool-border {
            border: 1px solid black;
            border-radius: 3px;
            overflow: auto;
            box-shadow: 0px 0px 3px 2px #b1b1b1;
        }
        .login-box-body p{
            color:white;
            margin: 4px 0;
        }
        button {
            background-color: rgba(0, 0, 0, 0.91);
            color: #ffffff;
            padding: 6px 0px;
            transition: background-color .4s;
            font-size: medium;
        }

        button:hover {
            background-color: #b8b8b8;
            color: #000000;
        }

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box cool-border">
    <div class="login-logo" style="margin-bottom: 0px;">
        <span style="font-size: 19px;">{{config('admin.title')}}</span>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p>請輸入帳號、密碼</p>
        <form action="{{ route('admin.login.auth') }}" method="post">
            @csrf
            @if($errors->has('account'))
                <label class="control-label text-danger" for="inputError">
                    ❌{{ $errors->first('account') }}
                </label>
            @endif
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="帳號" name="username" value="{{ old('username') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">
                <input type="password" class="form-control" placeholder="密碼" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row btnF mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn-primary" style="padding: 5px 20px">登入</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
<script src="{{asset('template_dist/js/sweetalert2.js')}}"></script>
<script>
    @if(session('web_status'))
    setTimeout(function (){
        showToast("{{session('web_status_description')}}", "{{session('web_status')}}",2000);
            <?php session()->forget('web_status_description');session()->forget('web_status'); // 清除 session?>
    },200)
    @endif
</script>
</html>
