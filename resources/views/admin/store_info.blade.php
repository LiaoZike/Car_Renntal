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
            <h1>店家資訊管理</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                新增店家
            </button>
        </div>
    </div>
    
    <div class="row" style="background-color: #f8f9fa; padding: 5px; border-radius: 5px;">
        <div class="col-md-12">
            <table id="location_table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>編號</th>
                        <th>名稱</th>
                        <th>縣市</th>
                        <th>區域</th>
                        <th>地址</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($locations))
                    @foreach($locations as $location)
                        <tr id="location_{{ $location->loc_id }}">
                            <td>{{ $location->loc_id }}</td>
                            <td>{{ $location->loc_name }}</td>
                            <td>{{ $location->city }}</td>
                            <td>{{ $location->district }}</td>
                            <td>{{ $location->address }}</td>
                            <td>
                                <button type="button" class="btn btn-primary edit_location" data-id="{{ $location->loc_id }}">編輯</button>
                                <button type="button" class="btn btn-danger delete_location" data-id="{{ $location->loc_id }}">刪除</button>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 新增店家模態框 -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">新增店家</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLocationForm">
                    <div class="mb-3">
                        <label for="new_loc_name" class="form-label">名稱</label>
                        <input type="text" class="form-control" id="new_loc_name" name="loc_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_city" class="form-label">縣市</label>
                        <input type="text" class="form-control" id="new_city" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_district" class="form-label">區域</label>
                        <input type="text" class="form-control" id="new_district" name="district" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_address" class="form-label">地址</label>
                        <input type="text" class="form-control" id="new_address" name="address" required>
                    </div>
                    <button type="submit" class="btn btn-primary">新增</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 編輯店家模態框 -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLocationModalLabel">編輯店家</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLocationForm">
                    <input type="hidden" id="edit_loc_id" name="loc_id">
                    <div class="mb-3">
                        <label for="edit_loc_name" class="form-label">名稱</label>
                        <input type="text" class="form-control" id="edit_loc_name" name="loc_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_city" class="form-label">縣市</label>
                        <input type="text" class="form-control" id="edit_city" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_district" class="form-label">區域</label>
                        <input type="text" class="form-control" id="edit_district" name="district" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">地址</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
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

    /* 讓表格文字自動換行 */
    #insurance_table td {
        word-wrap: break-word;
        white-space: normal;
    }
    option{
        background-color: #FFFFFF !important;
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
    $('#location_table').DataTable();

    // 新增店家
    $('#addLocationForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: `/manager/location/add`,
            method: 'POST',
            data: formData + '&_token={{ csrf_token() }}',
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function () {
                showToast('新增失敗，請稍後再試。', 'error');
            }
        });
    });

    // 編輯店家
    $(document).on('click', '.edit_location', function () {
        const locationId = $(this).data('id');

        $.ajax({
            url: `/manager/location/${locationId}/edit`,
            method: 'GET',
            success: function (response) {
                $('#edit_loc_id').val(response.loc_id);
                $('#edit_loc_name').val(response.loc_name);
                $('#edit_city').val(response.city);
                $('#edit_district').val(response.district);
                $('#edit_address').val(response.address);
                $('#editLocationModal').modal('show');
            },
            error: function () {
                showToast('無法獲取店家資料，請稍後再試。', 'error');
            }
        });
    });

    $('#editLocationForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: `/manager/location/update`,
            method: 'POST',
            data: formData + '&_token={{ csrf_token() }}',
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function () {
                showToast('更新失敗，請稍後再試。', 'error');
            }
        });
    });

    // 刪除店家
    $(document).on('click', '.delete_location', function () {
        const locationId = $(this).data('id');

        if (confirm(`確定要刪除此店家 (#${locationId}) 嗎？`)) {
            $.ajax({
                url: `/manager/location/${locationId}/delete`,
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
