<div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>
<header class="site-navbar site-navbar-target" role="banner" style="background-color:#eac680">
    <div class="container">
        <div class="row align-items-center position-relative">

            <div class="col-9 col-md-3">
                <div class="site-logo">
                    <a href="{{route('home')}}"><strong>123．租車</strong></a>
                </div>
            </div>

            <div class="col-3 col-md-9  text-right">
                <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>
                <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                    <ul class="site-menu main-menu js-clone-nav ml-auto ">
                        <li class="active"><a href="{{route('home')}}" class="nav-link">首頁</a></li>
                        <li><a href="{{route('rental_search')}}" class="nav-link">立即租車</a></li>
                        <li><a href="{{route('notice')}}" class="nav-link">注意事項</a></li>
                        @if(session()->has('user_data')&&session('user_data.active')===true)
{{--                        <li class="member_manager"><a href="{{route('google.auth.logout')}}" class="nav-link">會員管理</a></li>--}}
                        <li class="nav-item dropdown member_manager">
                            <a href="#" class="nav-link dropdown-toggle" id="memberDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                會員專區
                            </a>
                            <div class="dropdown-menu" aria-labelledby="memberDropdown">
                                <a class="dropdown-item member_center_btn" href="{{ route('member_center') }}">會員中心</a>
                                <a class="dropdown-item logout_btn" href="{{ route('google.auth.logout') }}">登出</a>
                            </div>
                        </li>
                        @else
                        <li class="member_manager"><a href="{{route('google.auth.page')}}" class="nav-link">登入</a></li>
                        @endif
                </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

