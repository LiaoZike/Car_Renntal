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
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">車型與車輛管理</h1>
            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModelModal">
                新增車型
            </button>
        </div>
    </div>

    <div class="row" style="overflow: auto;">
        <div class="col-md-12">
            <table id="model_table" class="table table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th>車型編號</th>
                        <th>品牌</th>
                        <th>車型名稱</th>
                        <th>車型類別</th>
                        <th>燃料類型</th>
                        <th>引擎排氣量</th>
                        <th>變速箱</th>
                        <th>圖片</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($models as $model)
                        <!-- 車型行 -->
                        <tr class="table-light table_model_row">
                            <td>{{ $model['model_id'] }}</td>
                            <td>{{ $model['brand'] }}</td>
                            <td>{{ $model['model_name'] }}</td>
                            <td>{{ $model['car_type'] }}</td>
                            <td>{{ $model['fuel_type'] }}</td>
                            <td>{{ $model['engine_cc'] }} cc</td>
                            <td>{{ $model['transmission'] == 1 ? '自排' : '手排' }}</td>
                            <td><img src="{{ asset($model['image_url']) }}" alt="車型圖片"></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit_model" data-id="{{ $model['model_id'] }}">編輯車型</button>
                                <button class="btn btn-danger btn-sm delete_model" data-id="{{ $model['model_id'] }}">刪除車型</button>
                                <button class="btn btn-success btn-sm add_car" data-id="{{ $model['model_id'] }}">新增汽車</button>
                            </td>
                        </tr>
                        <!-- 車輛列表 -->
                        <tr>
                            <td colspan="9" class="p-0">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>車輛編號</th>
                                            <th>車牌號碼</th>
                                            <th>日租金</th>
                                            <th>逾期費用</th>
                                            <th>製造年份</th>
                                            <th>座位數</th>
                                            <th>顏色</th>
                                            <th>里程數</th>
                                            <th>狀態</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($model['cars'] as $car)
                                            <tr class="table_car_row">
                                                <td>{{ $car['car_id'] }}</td>
                                                <td>{{ $car['plate_number'] }}</td>
                                                <td>{{ $car['daily_fee'] }} 元</td>
                                                <td>{{ $car['late_fee'] }} 元</td>
                                                <td>{{ $car['year_made'] }}</td>
                                                <td>{{ $car['seat_num'] }}</td>
                                                <td>{{ $car['color'] }}</td>
                                                <td>{{ $car['mileage'] }} 公里</td>
                                                <td>{{ $car['car_status'] }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit_car" data-id="{{ $car['car_id'] }}">編輯</button>
                                                    <button class="btn btn-danger btn-sm delete_car" data-id="{{ $car['car_id'] }}">刪除</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 新增車型模態框 -->
<div class="modal fade" id="addModelModal" tabindex="-1" aria-labelledby="addModelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModelModalLabel">新增車型</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addModelForm">
                    <div class="mb-3">
                        <label for="brand" class="form-label">品牌</label>
                        <input type="text" class="form-control" id="brand" name="brand" required>
                    </div>
                    <div class="mb-3">
                        <label for="model_name" class="form-label">車型名稱</label>
                        <input type="text" class="form-control" id="model_name" name="model_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="car_type" class="form-label">車型類別</label>
                        <select class="form-select" id="car_type" name="car_type" required>
                            <option value="Compact">Compact</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="MPV">MPV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fuel_type" class="form-label">燃料類型</label>
                        <select class="form-select" id="fuel_type" name="fuel_type" required>
                            <option value="Gasoline">Gasoline</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="engine_cc" class="form-label">引擎排氣量 (cc)</label>
                        <input type="number" class="form-control" id="engine_cc" name="engine_cc" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="transmission" class="form-label">變速箱</label>
                        <select class="form-select" id="transmission" name="transmission" required>
                            <option value="1">自排</option>
                            <option value="0">手排</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">圖片 URL</label>
                        <input class="form-control" id="image_url" name="image_url" required>
                    </div>
                    <button type="submit" class="btn btn-primary">新增</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 新增汽車模態框 -->
<div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCarModalLabel">新增汽車</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCarForm">
                    <input type="hidden" id="car_model_id" name="model_id">
                    <h5 class="text-center py-1" style="color:black; border-bottom:2px solid #055">《汽車資訊》</h5>
                    <div class="mb-3">
                        <label for="plate_number" class="form-label">車牌號碼</label>
                        <input type="text" class="form-control" id="plate_number" name="plate_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="vin" class="form-label">車身號碼</label>
                        <input type="text" class="form-control" id="vin" name="vin" required>
                    </div>
                    <div class="mb-3">
                        <label for="year_made" class="form-label">製造年份</label>
                        <input type="number" class="form-control" id="year_made" name="year_made" required min="1980">
                    </div>
                    <div class="mb-3">
                        <label for="mileage" class="form-label">里程數</label>
                        <input type="number" class="form-control" id="mileage" name="mileage" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="seat_num" class="form-label">座位數</label>
                        <input type="number" class="form-control" id="seat_num" name="seat_num" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">顏色</label>
                        <input type="text" class="form-control" id="color" name="color" required>
                    </div>
                    <h5 class="text-center py-1 mt-2" style="color:black; border-bottom:2px solid #055">《費用狀態資訊》</h5>

                    <div class="mb-3">
                        <label for="daily_fee" class="form-label">日租金</label>
                        <input type="number" class="form-control" id="daily_fee" name="daily_fee" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="late_fee" class="form-label">逾期費用</label>
                        <input type="number" class="form-control" id="late_fee" name="late_fee" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="car_status" class="form-label">狀態</label>
                        <select class="form-select" id="car_status" name="car_status" required>
                            <option value="available">可用</option>
                            <option value="maintenance">維修中</option>
                            <option value="disable">停用</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="loc_id" class="form-label">地點</label>
                        <select class="form-select" id="loc_id" name="loc_id" required>
                            @if(!empty($locations))
                            @foreach($locations as $location)
                                <option value="{{ $location->loc_id }}">{{ $location->loc_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">備註</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">新增</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 編輯車型模態框 -->
<div class="modal fade" id="editModelModal" tabindex="-1" aria-labelledby="editModelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModelModalLabel">編輯車型</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editModelForm">
                    <input type="hidden" id="edit_model_id" name="model_id">
                    <div class="mb-3">
                        <label for="edit_brand" class="form-label">品牌</label>
                        <input type="text" class="form-control" id="edit_brand" name="brand" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_model_name" class="form-label">車型名稱</label>
                        <input type="text" class="form-control" id="edit_model_name" name="model_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_car_type" class="form-label">車型類別</label>
                        <select class="form-select" id="edit_car_type" name="car_type" required>
                            <option value="Compact">Compact</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="MPV">MPV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fuel_type" class="form-label">燃料類型</label>
                        <select class="form-select" id="edit_fuel_type" name="fuel_type" required>
                            <option value="Gasoline">Gasoline</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_engine_cc" class="form-label">引擎排氣量 (cc)</label>
                        <input type="number" class="form-control" id="edit_engine_cc" name="engine_cc" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_transmission" class="form-label">變速箱</label>
                        <select class="form-select" id="edit_transmission" name="transmission" required>
                            <option value="1">自排</option>
                            <option value="0">手排</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image_url" class="form-label">圖片 URL</label>
                        <input class="form-control" id="edit_image_url" name="image_url" required>
                    </div>
                    <button type="submit" class="btn btn-primary">儲存變更</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 編輯汽車模態框 -->
<div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCarModalLabel">編輯汽車</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCarForm">
                    <input type="hidden" id="edit_car_id" name="car_id">
                    <h5 class="text-center py-1" style="color:black; border-bottom:2px solid #055">《汽車資訊》</h5>
                    <div class="mb-3">
                        <label for="edit_plate_number" class="form-label">車牌號碼</label>
                        <input type="text" class="form-control" id="edit_plate_number" name="plate_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_vin" class="form-label">車身號碼</label>
                        <input type="text" class="form-control" id="edit_vin" name="vin" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_year_made" class="form-label">製造年份</label>
                        <input type="number" class="form-control" id="edit_year_made" name="year_made" required min="1980">
                    </div>
                    <div class="mb-3">
                        <label for="edit_mileage" class="form-label">里程數</label>
                        <input type="number" class="form-control" id="edit_mileage" name="mileage" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_seat_num" class="form-label">座位數</label>
                        <input type="number" class="form-control" id="edit_seat_num" name="seat_num" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_color" class="form-label">顏色</label>
                        <input type="text" class="form-control" id="edit_color" name="color" required>
                    </div>
                    <h5 class="text-center py-1 mt-2" style="color:black; border-bottom:2px solid #055">《費用狀態資訊》</h5>

                    <div class="mb-3">
                        <label for="edit_daily_fee" class="form-label">日租金</label>
                        <input type="number" class="form-control" id="edit_daily_fee" name="daily_fee" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_late_fee" class="form-label">逾期費用</label>
                        <input type="number" class="form-control" id="edit_late_fee" name="late_fee" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_car_status" class="form-label">狀態</label>
                        <select class="form-select" id="edit_car_status" name="car_status" required>
                            <option value="available">可用</option>
                            <option value="maintenance">維修中</option>
                            <option value="disable">停用</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_loc_id" class="form-label">地點</label>
                        <select class="form-select" id="edit_loc_id" name="loc_id" required>
                            @if(!empty($locations))
                            @foreach($locations as $location)
                                <option value="{{ $location->loc_id }}">{{ $location->loc_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">備註</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
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
    ::selection {
        background: #7ac0ec !important;
        color: black;
    }
    .table-light {
        background-color: #f8f9fa !important;
    }
    /* 車輛列表背景顏色 */
    .table-secondary {
        background-color: #e9ecef !important;
    }
    /* 車型與車輛之間的間距 */
    tr.table-light + tr {
        border-top: 2px solid #dee2e6;
    }
    /* 圖片樣式 */
    #model_table img {
        max-width: 200px;
        height: auto;
    }
    /* 表格文字自動換行 */
    #model_table td {
        word-wrap: break-word;
        white-space: normal;
    }
    .table_model_row td{
        background-color:rgb(235, 211, 160) !important;
        border: 1px solid #BBB;
    }
    .table-secondary th{
        border: 1px solid #444 !important;
        border-top: 3px solid #444 !important;
    }
    .table_car_row td{
        border: 1px solid #444 !important;
    }
    tr,td,th{
        text-align: center !important;
        vertical-align: middle !important;
    }
    .table_car_row {
        background-color:rgb(255, 255, 255) !important;
    }
</style>
@endsection

@section('main_js')
<script>
    $(document).ready(function () {
        // 新增車型
        $('#addModelForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: `/manager/car_management/add_model`,
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
                    showToast('新增車型失敗，請稍後再試。', 'error');
                }
            });
        });
        //刪除車型
        $(document).on('click', '.delete_model', function () {
            const modelId = $(this).data('id');
            if (confirm(`確定要刪除此車型 (#${modelId}) 嗎？`)) {
                $.ajax({
                    url: `/manager/car_management/${modelId}/delete_model`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.message) {
                            showToast(response.message, 'info'); // 顯示提示消息
                        } else if (response.success) {
                            location.reload(); // 重新加載頁面
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.error) {
                            showToast(xhr.responseJSON.error, 'error');
                        } else {
                            showToast('刪除車型失敗，請稍後再試。', 'error');
                        }
                    }
                });
            }
        });

        // 編輯車型
        $(document).on('click', '.edit_model', function () {
            const modelId = $(this).data('id');
            $.ajax({
                url: `/manager/car_management/${modelId}/edit_model`,
                method: 'GET',
                success: function (response) {
                    $('#edit_model_id').val(response.model_id);
                    $('#edit_brand').val(response.brand);
                    $('#edit_model_name').val(response.model_name);
                    $('#edit_car_type').val(response.car_type);
                    $('#edit_fuel_type').val(response.fuel_type);
                    $('#edit_engine_cc').val(response.engine_cc);
                    $('#edit_transmission').val(response.transmission);
                    $('#edit_image_url').val(response.image_url);
                    $('#editModelModal').modal('show');
                },
                error: function () {
                    showToast('無法獲取車型資料，請稍後再試。', 'error');
                }
            });
        });

        // 提交編輯車型表單
        $('#editModelForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: `/manager/car_management/update_model`,
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

        // 點擊新增汽車按鈕，打開模態框
        $(document).on('click', '.add_car', function () {
            const modelId = $(this).data('id');
            $('#car_model_id').val(modelId);
            $('#addCarModal').modal('show');
        });

        // 提交新增汽車表單
        $('#addCarForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: `/manager/car_management/add_car`,
                method: 'POST',
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    if (response.message) {
                        showToast(response.message, 'info'); // 顯示提示消息
                    } else if (response.success) {
                        location.reload(); // 重新加載頁面
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 400 && xhr.responseJSON.error) {
                        showToast(xhr.responseJSON.error, 'error');
                    } else {
                        showToast('新增失敗，請稍後再試。', 'error');
                    }
                }
            });
        });
        //刪除汽車表單
        $(document).on('click', '.delete_car', function () {
            const carId = $(this).data('id');
            if (confirm(`確定要刪除此汽車 (#${carId}) 嗎？`)) {
                $.ajax({
                    url: `/manager/car_management/${carId}/delete_car`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.message) {
                            showToast(response.message, 'info'); // 顯示提示消息
                        } else if (response.success) {
                            location.reload(); // 重新加載頁面
                        }
                    },
                    error: function (xhr) {
                        // 檢查是否有錯誤消息
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            showToast(xhr.responseJSON.error, 'error'); // 顯示後端返回的錯誤消息
                        } else {
                            showToast('刪除汽車失敗，請稍後再試。', 'error'); // 顯示通用錯誤消息
                        }
                    }
                });
            }
        });
        // 點擊編輯汽車按鈕，打開模態框並加載數據
        $(document).on('click', '.edit_car', function () {
            const carId = $(this).data('id');
            $.ajax({
                url: `/manager/car_management/${carId}/edit_car`,
                method: 'GET',
                success: function (response) {
                    $('#edit_car_id').val(response.car_id);
                    $('#edit_plate_number').val(response.plate_number);
                    $('#edit_daily_fee').val(response.daily_fee);
                    $('#edit_late_fee').val(response.late_fee);
                    $('#edit_year_made').val(response.year_made);
                    $('#edit_vin').val(response.vin);
                    $('#edit_seat_num').val(response.seat_num);
                    $('#edit_color').val(response.color);
                    $('#edit_mileage').val(response.mileage);
                    $('#edit_car_status').val(response.car_status);
                    $('#edit_loc_id').val(response.loc_id);
                    $('#edit_notes').val(response.notes);
                    $('#editCarModal').modal('show');
                },
                error: function () {
                    showToast('無法獲取汽車資料，請稍後再試。', 'error');
                }
            });
        });

        // 提交編輯汽車表單
        $('#editCarForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            $.ajax({
                url: `/manager/car_management/update_car`,
                method: 'POST',
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    }else{
                        showToast(response.message, 'info'); // 顯示提示消息
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        showToast(xhr.responseJSON.error, 'error');
                    } else {
                        showToast('更新失敗，請稍後再試。', 'error');
                    }
                }
            });
        });
    });
</script>
@endsection
