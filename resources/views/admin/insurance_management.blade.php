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
    <div class="container-fluid" style="overflow:auto">
        <div class="row">
            <div class="col-md-12">
                <h1>保險管理</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addInsuranceModal">
                    新增保險方案
                </button>
            </div>
        </div>
        <div class="row" style="background-color: #f8f9fa; padding: 5px; border-radius: 5px;">
            <div class="col-md-12">
                <table id="insurance_table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>保險編號</th>
                        <th>保險名稱</th>
                        <th>保險費用</th>
                        <th>保險內容</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($insurances))
                        @foreach($insurances as $insurance)
                            <tr id="{{ $insurance->insurance_id }}">
                                <td>{{ $insurance->insurance_id }}</td>
                                <td>{{ $insurance->ins_name }}</td>
                                <td>{{ $insurance->ins_fee }} 元</td>
                                <td>{{ $insurance->coverage }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary edit_insurance" data-id="{{ $insurance->insurance_id }}">編輯</button>
                                    <button type="button" class="btn btn-danger delete_insurance" data-id="{{ $insurance->insurance_id }}">刪除</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 新增保險方案模態框 -->
    <div class="modal fade" id="addInsuranceModal" tabindex="-1" aria-labelledby="addInsuranceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInsuranceModalLabel">新增保險方案</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addInsuranceForm">
                        <div class="mb-3">
                            <label for="new_ins_name" class="form-label">保險名稱</label>
                            <input type="text" class="form-control" id="new_ins_name" name="ins_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_ins_fee" class="form-label">保險費用</label>
                            <input type="number" class="form-control" id="new_ins_fee" name="ins_fee" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="new_coverage" class="form-label">保險內容</label>
                            <textarea class="form-control" id="new_coverage" name="coverage" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">新增</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editInsuranceModal" tabindex="-1" aria-labelledby="editInsuranceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInsuranceModalLabel">編輯保險資訊</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editInsuranceForm">
                        <input type="hidden" id="insurance_id" name="insurance_id">
                        <div class="mb-3">
                            <label for="ins_name" class="form-label">保險名稱</label>
                            <input type="text" class="form-control" id="ins_name" name="ins_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="ins_fee" class="form-label">保險費用</label>
                            <input type="number" class="form-control" id="ins_fee" name="ins_fee" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="coverage" class="form-label">保險內容</label>
                            <textarea class="form-control" id="coverage" name="coverage" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">儲存變更</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main_css')
<style>
    textarea, input, label ,.form-control,.modal h5{
        color: black !important;
    }
    option{
        background-color: #FFFFFF !important;
    }
    /* 讓表格文字自動換行 */
    #insurance_table td {
        word-wrap: break-word;
        white-space: normal;
    }

    /* 可選：限制表格列的最大寬度 */
    #insurance_table td:nth-child(3) { /* 第三列（保險內容） */
        max-width: 300px; /* 可根據需要調整 */
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
        $('#insurance_table').DataTable();

        // 編輯按鈕點擊事件
        $(document).on('click', '.edit_insurance', function () {
            const insuranceId = $(this).data('id');

            // 發送 AJAX 請求獲取保險資料
            $.ajax({
                url: `/manager/insurance/${insuranceId}/edit`, // 動態插入 insuranceId
                method: 'GET',
                success: function (response) {
                    
                    // 填充模態框表單
                    $('#insurance_id').val(response.insurance_id);
                    $('#ins_name').val(response.ins_name);
                    $('#ins_fee').val(response.ins_fee);
                    $('#coverage').val(response.coverage);

                    // 顯示模態框
                    $('#editInsuranceModal').modal('show');
                },
                error: function () {
                    showToast('無法獲取保險資料，請稍後再試。', 'error');
                }
            });
        });

        // 表單提交事件
        $('#editInsuranceForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: `/manager/insurance/update`,
                method: 'POST',
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    if (response.message) {
                        showToast(response.message, 'info'); // 顯示提示消息
                    } else if (response.success) {
                        location.reload(); // 重新加載頁面
                    }
                },
                error: function () {
                    showToast('更新失敗，請稍後再試。', 'error');
                }
            });
        });

        // 新增保險方案表單提交事件
        $('#addInsuranceForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: `/manager/insurance/add`,
                method: 'POST',
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    if (response.success) {
                        location.reload(); // 重新加載頁面
                    }
                },
                error: function () {
                    showToast('新增失敗，請稍後再試。', 'error');
                }
            });
        });

        // 刪除按鈕點擊事件
        $(document).on('click', '.delete_insurance', function () {
            const insuranceId = $(this).data('id');

            if (confirm(`確定要刪除此保險方案 (#${insuranceId}) 嗎？`)) {
                $.ajax({
                    url: `/manager/insurance/${insuranceId}/delete`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            location.reload(); // 重新加載頁面
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.error) {
                            showToast(xhr.responseJSON.error, 'error');
                        } else {
                            showToast('刪除失敗，請稍後再試。', 'error');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
