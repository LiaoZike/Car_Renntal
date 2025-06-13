<div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
    <!-- 新增：手機版關閉按鈕 -->
    <div class="sidebar-close d-block d-md-none text-end p-2">
        <a href="#" id="close-sidebar"><i class="zmdi zmdi-close" style="font-size: 24px; color: white;"></i></a>
    </div>
    <div class="brand-logo">
        <a class="text-decoration-none" href="{{route('admin.home')}}">
            <span class="user-profile"><img src="{{asset('media/admin/me.png')}}" class="img-circle" alt="user avatar"></span>
            <h5 class="logo-text">{{ session('username') }}&ensp;你好!</h5>
        </a>
    </div>

    <ul class="sidebar-menu do-nicescrol">
        <li class="sidebar-header">帳號功能</li>
        <li class="logout-btn"><a href="{{route('admin.logout')}}">
                <i class="zmdi zmdi-close-circle"></i> <span>登出</span>
            </a></li>
    </ul>
    <ul class="sidebar-menu do-nicescrol">
        <li class="sidebar-header">主列表</li>
        <li><a href="{{route('admin.home')}}">
                <i class="zmdi zmdi-view-dashboard"></i><span>主控臺</span>
            </a></li>
        <li><a href="{{route('admin.rental','all')}}">
                <i class="zmdi zmdi-accounts-list"></i> <span>租借申請清單</span>
            </a></li>
        <li class="sidebar-header">DataBase View</li>
        <li>
            <a href="{{ route('admin.carManagement') }}">
                <i class="fa fa-car" aria-hidden="true"></i><span>車型管理</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.insuranceManagement') }}">
                <i class="fa fa-file-text" aria-hidden="true"></i><span>保險方案管理</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.storeInfo') }}">
                <i class="fa fa-map-marker" aria-hidden="true"></i><span>店家資訊</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.memberManagement') }}">
                <i class="fa fa-user" aria-hidden="true"></i><span>會員資訊</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.pictureManagement') }}">
                <i class="fa fa-file-image-o" aria-hidden="true"></i><span>圖片管理</span>
            </a>
        </li>
    </ul>
</div>




