@php use Illuminate\Support\Str; @endphp
@extends('admin/blade/master')

@section('source_css')
    <link rel="stylesheet" href="{{ asset('dist/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/dataTables.dateTime.min.css') }}">
@endsection
@section('source_js')
    <script src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.dateTime.min.js') }}"></script>
    <script src="{{ asset('dist/js/sortable.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist/js/new_moment.min.js') }}"></script>
@endsection

@section('main_section')
<div class="container-fluid">
    <h1 class="mb-4">會員列表</h1>

    <!-- 黑名單管理按鈕 -->
    <div class="mb-3 text-end">
        <button class="btn btn-danger" id="blacklist-btn">
            <i class="fa fa-ban" aria-hidden="true"></i> 黑名單管理
        </button>
    </div>

    <!-- 會員列表表格 -->
    <table id="members_table" class="table table-bordered" style="background-color: #f8f9fa; padding: 5px; border-radius: 5px;">
        <thead>
            <tr class="table-primary">
                <th>會員編號</th>
                <th>Google ID</th>
                <th>電子郵件</th>
                <th>電話</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                <tr>
                    <td>{{ $member->member_id }}</td>
                    <td>{{ $member->google_id }}</td>
                    <td>{{ $member->gmail }}</td>
                    <td>{{ $member->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- 黑名單管理模態框 -->
<div class="modal fade" id="blacklistModal" tabindex="-1" aria-labelledby="blacklistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blacklistModalLabel">黑名單管理</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3 class="text-warning">未來開發...</h3>
                <p>此功能目前尚未完成，敬請期待！</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main_css')
<style>
    textarea, input, label, .form-control, .modal h5 {
        color: black !important;
    }

    /* 表格樣式 */
    table {
        text-align: center;
    }
    tr, th, td {
        border: 1px solid #000 !important;
    }

    /* 黑名單按鈕樣式 */
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* 黑名單模態框樣式 */
    .modal-body h3 {
        font-weight: bold;
        color: #ff6f61;
    }
    ::selection {
        background: #7ac0ec !important;
        color: black;
    }
</style>
@endsection

@section('main_js')
<script>
    $(document).ready(function () {
        // 點擊黑名單管理按鈕，顯示模態框
        $('#blacklist-btn').on('click', function () {
            $('#blacklistModal').modal('show');
        });
    });
</script>
@endsection
