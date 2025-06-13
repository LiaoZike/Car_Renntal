@extends('admin/blade/master')

@section('source_css')
    <link rel="stylesheet" href="{{ asset('dist/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/dataTables.dateTime.min.css') }}">
@endsection
@section('source_js')
{{--    <script src="{{ asset('dist/js/test.js') }}"></script>--}}
    <script src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.dateTime.min.js') }}"></script>
    <script src="{{ asset('dist/js/sortable.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.buttons.min.js') }}"></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>--}}
    <script src="{{ asset('dist/js/new_moment.min.js') }}"></script>
@endsection

@section('main_css')
    <style>
        .titleRow {
            display: flex;
            flex-direction: row;
        }
        .titleCol {
            min-width: 130px;
            background-color: white;
            padding: 5px 20px;
            text-align: center;
            cursor: pointer;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            text-decoration: none;
            font-size: large;
            color: black;
        }

        .titleCol:hover{
            color:black;
        }
        .nowPageRange {
            padding-top: 10px;
            border-top: 3px solid rgba(120, 120, 120, 0.48);
        }

        .searchTr th{
            border-left:black 1px solid !important;
            border-bottom: black 1px solid;
        }
        .searchTr th input::placeholder{
            color: #000;
            opacity: 0.3;
        }

        .searchTr th:nth-child(10){
            border-right:black 1px solid;
        }
        #RentalInfoTable input {
            width: 100%;
        }

        body {
            overflow: scroll;
            min-width: 1px ;
        }

        .state_circle {
            display: inline-block;
            height: 10px;
            width: 10px;
            border-radius: 10px;
        }

        .close {
            color: black;
        }
        /* Table設定 */
        table ,label{
            border-collapse: collapse;
            color:black;
        }

        tfoot {
            display: table-row-group;
        }
        #RentalInfoTable {
            border: 1px solid rgba(153, 153, 153, 0.6);
            border-radius: 5px;
            padding: 10px;
        }
        thead, tbody, tfoot, tr, td, th {
            border-style: none;
        }

        .titleCol {
            display: inline-block;
            min-width: 140px;
            text-align: center;
            padding: 10px 15px;
            border-radius: 6px;
            /*!*color: white;*!*/
            /*text-decoration: none;*/
        }
        td {
            border-left: 1px solid #939393 !important;
        }

        .hv_grey:hover{
            transition: background-color .1s;
            background-color: rgb(230, 230, 230);
        }

        .hv_orange:hover{
            transition: background-color .1s;
            background-color: rgb(251, 232, 206);
        }

        .hv_green:hover{
            transition: background-color .1s;
            background-color: rgb(195, 255, 195);
        }
        .hv_red:hover{
            transition: background-color .1s;
            background-color: rgb(250, 183, 183);
        }
        .selected-grey {
            background-color: rgb(161, 161, 161) !important;
            color: black !important;
        }
        .selected-orange {
            background-color: rgb(255, 195, 81) !important;
            color: black !important;
        }
        .selected-green {
            background-color: rgb(79, 255, 79) !important;
            color: black !important;
        }
        .selected-red {
            background-color: rgb(253, 76, 76) !important;
            color: white !important;
        }

        .selected-pink{
            background-color: rgb(255, 152, 152) !important;
            color: white !important;
        }

        /* 狀態群組篩選 */
        .stateGroup label,.stateGroup input{
            cursor: pointer;
        }
        .stateGroup input:checked ~ label{
            text-decoration: none;
            opacity: 1;
        }
        .stateGroup label{
            padding-left: 1px;
            margin-bottom: 0px;
            /*user-select: none;*/
            opacity: 0.8;
            transition: all .5s;
            text-decoration: line-through;

        }
        .stateGroup input{
            display: inline;
            width: auto !important;
        }
        @media screen and (max-width: 768px) {
            .titleRow {
                display: block;
                text-align: center;
            }

            .nowPageRange {
                overflow-x: auto;

            }
        }



        th#TimeRange {
            padding: 4px;
            text-align: center;
        }
        th#TimeRange input {
            display: block;
            width: 45%;
            margin: 2px auto;
            box-sizing: border-box;
        }
        #StateCheckbox label{
            color:black;
        }

    </style>
@endsection
@section('main_section')
    <div class="d-flex flex-wrap">
        <div class="py-1">
            <a data-state="pending" class="filter-by-status titleCol hv_grey">
                <i class="fa fa-hourglass-start"></i> 未審核
            </a>
        </div>
        <div class="py-1">
            <a data-state="active_not_started" class="filter-by-status titleCol hv_orange">
                <i class="fa fa-cog"></i> 審過-未開始
            </a>
        </div>
        <div class="py-1">
            <a data-state="active_ongoing" class="filter-by-status titleCol hv_orange">
                <i class="fa fa-cog"></i> 審過-進行中
            </a>
        </div>
        <div class="py-1">
            <a data-state="completed" class="filter-by-status titleCol hv_green">
                <i class="fa fa-check-circle"></i> 訂單完成
            </a>
        </div>
        <div class="py-1">
            <a data-state="cancelled" class="filter-by-status titleCol hv_red">
                <i class="fa fa-window-close"></i> 訂單取消
            </a>
        </div>
        <div class="py-1">
            <a data-state="reject" class="filter-by-status titleCol hv_red">
                <i class="fa fa-window-close"></i> 訂單已拒絕
            </a>
        </div>
    </div>

    <div class="nowPageRange" style="background-color: white !important;">
        <table id="RentalInfoTable" class="table">
            <thead>
            <tr>
                <th data-sortable="true" scope="col">#</th>
                <th data-sortable="true" scope="col">申請人</th>
                <th data-sortable="true" scope="col">電話</th>
                <th data-sortable="true" scope="col">車型</th>
                <th data-sortable="true" scope="col">車牌</th>
                <th data-sortable="true" scope="col">站點</th>
                <th data-sortable="true" scope="col" colspan="2">租車時間(開始,結束)</th>
                <th data-sortable="true" scope="col">狀態</th>
                <th data-sortable="true" scope="col">付款</th>
                <th data-sortable="true" scope="col">費用</th>
                <th data-sortable="true" scope="col">功能</th>
            </tr>
            </thead>

            <tfoot>
            <tr class="searchTr">
                <th>R_ID
                <th>S_Mail</th>
                <th>M_Phone</th>
                <th>S_Car</th>
                <th>S_plate</th>
                <th>S_Location</th>
                <th id="TimeRange" colspan="2"></th>
                <th id="StateCheckbox" class="invisible">S_States</th>
                <th>S_Note</th>
                <th>S_Fee</th>
                <th class="d-none">function</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach($rentalOrders as $order)
                <tr
                    @if($order->rental_status == "pending") class="hv_grey"
                    @elseif($order->rental_status == "active_not_started") class="hv_orange"
                    @elseif($order->rental_status == "active_ongoing") class="hv_orange"
                    @elseif($order->rental_status == "completed") class="hv_green"
                    @elseif($order->rental_status == "cancelled") class="hv_red"
                    @elseif($order->rental_status == "reject") class="hv_red"
                    @endif
                >
                    <th scope="row" style="width: 50px">{{ $order->id }}</th>
                    <td>{{ $order->gmail }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->full_model_name }}</td>
                    <td>{{ $order->plate_number }}</td>
                    <td>{{ $order->loc_name }}</td>
                    <td>{{ Str::substr($order->start_date, 0, 19) }}</td>
                    <td>{{ Str::substr($order->end_date, 0, 19) }}</td>
                    <td>
                        @if($order->rental_status == "pending")
                            <span class="state_circle" style="background-color:grey;"></span>
                            <b style="color:grey">未審核</b>
                        @elseif($order->rental_status == "active_not_started")
                            <span class="state_circle" style="background-color:orange;"></span>
                            <b style="color:orange">審過-未開始</b>
                        @elseif($order->rental_status == "active_ongoing")
                            <span class="state_circle" style="background-color:orange;"></span>
                            <b style="color:orange">審過-進行中</b>
                        @elseif($order->rental_status == "completed")
                            <span class="state_circle" style="background-color:green;"></span>
                            <b style="color:green">已完成</b>
                        @elseif($order->rental_status == "cancelled")
                            <span class="state_circle" style="background-color:red;"></span>
                            <b style="color:red">已取消</b>
                        @elseif($order->rental_status == "reject")
                            <span class="state_circle" style="background-color:red;"></span>
                            <b style="color:red">已拒絕</b>
                        @endif
                    </td>
                    <td>@if($order->payment_status==1) <i class="fa fa-check" aria-hidden="true"></i>已付款 @else 未付款 @endif</td>
                    <td>NT$ {{ (int)$order->total_cost }}</td>
                    <td style="width: 100px">
                        <a href="javascript:void(0);"
                           class="btn btn-primary btn-sm view-edit-btn"
                           data-id="{{ $order->id }}"
                           data-phone="{{ $order->phone }}"
                           data-gmail="{{ $order->gmail }}"
                           data-model="{{ $order->full_model_name }}"
                           data-car_type="{{ $order->car_type }}"
                           data-fuel_type="{{ $order->fuel_type }}"
                           data-engine_cc="{{ $order->engine_cc }}"
                           data-transmission="{{ $order->transmission }}"
                           data-image_url="{{ asset($order->image_url) }}"
                           data-plate="{{ $order->plate_number }}"
                           data-daily_fee="{{ $order->daily_fee }}"
                           data-late_fee="{{ $order->late_fee }}"
                           data-seat_num="{{ $order->seat_num }}"
                           data-loc="{{ $order->loc_name }}"
                           data-start="{{ $order->start_date }}"
                           data-end="{{ $order->end_date }}"
                           data-cost="{{ $order->total_cost }}"
                           data-status="{{ $order->rental_status }}"
                           data-method="{{ $order->method }}"
                           data-payment_status="{{ $order->payment_status }}"
                           data-ins_id="{{$order->insurance_id}}"
                           data-ins_name="{{ $order->ins_name }}"
                           data-coverage="{{ $order->coverage }}"
                           data-ins_fee="{{ $order->ins_fee }}"
                           data-show_account_calc="{{ $order->show_account_calc }}"
                           data-total_days="{{ $order->total_days }}"
                        >
                            查看/編輯
                        </a>
{{--                        <a href="" class="btn btn-primary btn-sm">查看/編輯</a>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('second_section')
@include('admin/components/order_edit_modal')
@endsection
@section('main_js')
    <style>
        #RentalInfoTable_wrapper{
            overflow: auto;
        }
        #orderModalLabel{
            color:black;
        }
        .modal-header{
            background-color: rgba(251, 178, 106, 0.56);
        }
        #modal-image_url{
            border: 2px solid goldenrod;
            width: 50%;
        }
        #orderForm *{
            color:black;
        }
        #orderForm span{
            user-select: text !important;
        }
        select,option{
            background-color: white !important;
        }
        #modal-show_account_calc_new{
            color:red
        }
        ::selection {
            background: #7ac0ec !important;
            color: black;
        }
    </style>
    <script>
        $(document).ready(function () {
            table = new DataTable('#RentalInfoTable', {
                "aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]], //筆數設定
                "order": [[0, "desc"]], //降逆排序
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            let column = this;
                            let title = column.footer().textContent;
                            // Create input element
                            let input = document.createElement('input');
                            input.placeholder = title;
                            column.footer().replaceChildren(input);

                            // Event listener for user input
                            input.addEventListener('keyup', () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });

                        });
                }
            });
            /* 額外附加 日期篩選 */
            $(document).ready(function () {
                $('#TimeRange').html('<input type="text" id="min" name="min" placeholder="起始日期"> ~ <input type="text" id="max" name="max"  placeholder="結尾日期"></td>')
                let minDate, maxDate;
                let table1=DataTable.ext.search.push(function (settings, data, dataIndex) {
                    let min = minDate.val(); //篩選器-開始
                    let max = maxDate.val(); //篩選器-結束
                    let data_startdate = new Date(data[6]);
                    let data_enddate = new Date(data[7]);
                    let minDateVal = min ? new Date(min) : new Date(-8640000000000000); // 最小日期
                    let maxDateVal = max ? new Date(max) : new Date(8640000000000000);  // 最大日期

                    if (maxDateVal >= data_startdate && minDateVal <= data_enddate) {
                        return true; // 有交集
                    }
                    return false; // 沒有交集
                });
                minDate = new DateTime('#min', {
                    format: 'MMMM Do YYYY'
                });
                maxDate = new DateTime('#max', {
                    format: 'MMMM Do YYYY'
                });
                document.querySelectorAll('#min, #max').forEach((el) => {
                    el.addEventListener('change', () => table.draw());
                });
                $('#RentalInfoTable_filter input').attr('placeholder', 'Search All'); //額外附加:全體搜尋
            });

            $('#StateCheckbox').html(`\
            <div class="stateGroup">\
                <div><input class="status-filter" type="checkbox" id="chkAudit" name="pending" onchange="filterData()" checked="checked"><label for="chkAudit">未審核<\/label><\/div>\
                <div><input class="status-filter" type="checkbox" id="chkProcess_1" name="active_not_started" onchange="filterData()" checked="checked"><label for="chkProcess">審過-未開始<\/label><\/div>\
                <div><input class="status-filter" type="checkbox" id="chkProcess_2" name="active_ongoing" onchange="filterData()" checked="checked"><label for="chkProcess">審過-進行中<\/label><\/div>\
                <div><input class="status-filter" type="checkbox" id="chkComplete" name="completed" onchange="filterData()" checked="checked"><label for="chkComplete">訂單完成<\/label><\/div>\
                <div><input class="status-filter" type="checkbox" id="chkReturn" name="cancelled" onchange="filterData()" checked="checked"><label for="chkReturn">訂單取消<\/label><\/div>\
                <div><input class="status-filter" type="checkbox" id="chkReject" name="reject" onchange="filterData()" checked="checked"><label for="chkReturn">訂單拒絕<\/label><\/div>\
            <\/div>`);
        });


        // 狀態篩選
        function filterData() {
            selectedFilters = [];
            if ($('#chkAudit').prop('checked')) selectedFilters.push('未審核');
            if ($('#chkProcess_1').prop('checked')) selectedFilters.push('審過-未開始');
            if ($('#chkProcess_2').prop('checked')) selectedFilters.push('審過-進行中');
            if ($('#chkComplete').prop('checked')) selectedFilters.push('完成');
            if ($('#chkReturn').prop('checked')) selectedFilters.push('取消');
            if ($('#chkReject').prop('checked')) selectedFilters.push('已拒絕');
            // 檢查如果選擇過濾器數組為空，則不應用篩選
            if (selectedFilters.length > 0) {
                table.column(8).search(selectedFilters.join('|'), true, false);
            } else {
                table.column(8).search("");
            }
            table.draw();
        }
        //上方列同步checkbox功能
        $(document).ready(function () {
            function applyStatusStyle($btn, state) {
                $btn.addClass('selected');
                switch (state) {
                    case 'pending':
                        $btn.addClass('selected-grey');
                        break;
                    case 'active_not_started':
                    case 'active_ongoing':
                        $btn.addClass('selected-orange');
                        break;
                    case 'completed':
                        $btn.addClass('selected-green');
                        break;
                    case 'cancelled':
                        $btn.addClass('selected-red');
                        break;
                    case 'reject':
                        $btn.addClass('selected-pink');
                        break;
                }
            }
            //上方列初始化顯示顏色
            if ("{{$status}}" == 'all') {
                $('.filter-by-status').each(function () {
                    const $btn = $(this);
                    const state = $btn.data('state');
                    applyStatusStyle($btn, state);
                });
                filterData();
            } else { // 只套用特定狀態
                $(`.filter-by-status[data-state='{{$status}}']`).each(function () {
                    applyStatusStyle($(this), '{{$status}}');
                });
                $(document).ready(function (){
                    $(`.status-filter`).each(function (){
                        $(this).prop('checked', 0);
                    });
                    $(`.status-filter[name="{{$status}}"]`).prop('checked',1);
                    filterData();
                });
            }





            $('.filter-by-status').on('click', function () {
                const state = $(this).data('state');  // 取得 data-state 屬性
                const $checkbox = $(`input[type="checkbox"][name="${state}"]`);
                const $btn = $(this);

                $checkbox.prop('checked', !$checkbox.prop('checked'));
                filterData();

                $btn.removeClass('selected-grey selected-orange selected-green selected-red selected-pink');
                if ($btn.hasClass('selected')) {
                    $btn.removeClass('selected');
                } else{
                    $btn.addClass('selected');
                    switch (state) {
                        case 'pending':
                            $btn.addClass('selected-grey');
                            break;
                        case 'active_not_started':
                        case 'active_ongoing':
                            $btn.addClass('selected-orange');
                            break;
                        case 'completed':
                            $btn.addClass('selected-green');
                            break;
                        case 'cancelled':
                            $btn.addClass('selected-red');
                            break;
                        case 'reject':
                            $btn.addClass('selected-pink');
                            break;
                    }
                }
            });
        });
    </script>
    <script>
        let total_days,daily_fee
        //舊的資訊
        let insuranceOld,insuranceNameOld,paymentMethodOld,paymentStatusOld,orderStatusOld
        $(document).on('click', '.view-edit-btn', function () {
            $('#modal-id').text($(this).data('id'))
            $('#modal-phone').text($(this).data('phone'))
            $('#modal-gmail').text($(this).data('gmail'))
            $('#modal-model').text($(this).data('model'))
            $('#modal-car_type').text($(this).data('car_type'))
            $('#modal-fuel_type').text($(this).data('fuel_type'))
            $('#modal-engine_cc').text($(this).data('engine_cc'))
            $('#modal-transmission').text($(this).data('transmission'))
            $('#modal-image_url').attr('src', $(this).data('image_url'));
            $('#modal-plate').text($(this).data('plate'))
            $('#modal-daily_fee').text($(this).data('daily_fee'))
            $('#modal-late_fee').text($(this).data('late_fee'))
            $('#modal-seat_num').text($(this).data('seat_num'))
            $('#modal-loc').text($(this).data('loc'))
            $('#modal-start').text($(this).data('start'))
            $('#modal-end').text($(this).data('end'))
            $('#modal-cost').text($(this).data('cost'))
            $('#modal-method').text($(this).data('method'))
            $('#modal-ins_id').text($(this).data('ins_id'))
            $('#modal-payment_status').text(($(this).data('payment_status')==1)?"已付款":"未付款")
            $('#modal-ins_name').text($(this).data('ins_name'))
            $('#modal-coverage').text($(this).data('coverage'))
            $('#modal-ins_fee').text($(this).data('ins_fee'))
            $('#modal-show_account_calc').text($(this).data('show_account_calc'))

            total_days=$(this).data('total_days')
            daily_fee=$(this).data('daily_fee')
            const ins_id = $(this).data('ins_id'); // ex: 2
            const payment_method = $(this).data('method');
            const payment_status = $(this).data('payment_status'); // ex: 0 或 1
            orderStatusOld = $(this).data('status');
            if(orderStatusOld === "active_not_started" || orderStatusOld === "active_ongoing"){
                orderStatusOld="active"
            }
            //更新舊資料暫存
            insuranceOld=$(this).data('ins_id')
            insuranceNameOld=$(this).data('ins_name')
            paymentMethodOld = payment_method
            paymentStatusOld = (payment_status==1)?"已付款":"未付款";

            $('#modal-ins_select').val(ins_id);
            $('#modal-method_select').val(payment_method);
            $('#modal-payment_status_select').val(payment_status);
            $('#modal-status').val(orderStatusOld);

            $('#modal-show_account_calc_new').addClass('d-none');
            $('#orderModal').modal('show');
        });

        const insuranceData = @json($insurances);
        $(document).ready(function (){
            $('#modal-ins_select').on('change', function () {
                const selectedId = parseInt($(this).val());
                const selectedInsurance = insuranceData.find(ins => ins.insurance_id === selectedId);
                if (selectedInsurance) {
                    $('#modal-coverage').text(selectedInsurance.coverage);
                    ins_fee=selectedInsurance['ins_fee'];
                    show_account_calc = total_days+"天 × (車輛費"+daily_fee+" + 保險費"+ins_fee+") = "+ total_days*(daily_fee+ins_fee) +" 元";
                    $('#modal-show_account_calc_new').text(show_account_calc);
                    $('#modal-show_account_calc_new').removeClass('d-none');
                } else {
                    $('#modal-coverage').text('無對應保險說明');
                }
            });
        });

        //資料送出變更
        function status_replace(str){
            if(str==="pending") return "未審核"
            else if(str === "active") return "審核通過"
            else if(str === "completed") return "完成"
            else if(str === "cancelled") return "顧客取消"
            else if(str === "reject") return "拒絕"
            else return "未知"
        }
        function payment_replae(str){
            if(str==="cash") return "現金"
            else if(str === "credit") return "信用卡"
            else if(str === "linepay") return "Line Pay"
            else return "未知"
        }

        $('#saveChangesBtn').on('click', function () {
            let changes = [];
            // 比較保險方案
            let insuranceNew = $('#modal-ins_select option:selected').val();
            if (insuranceOld != insuranceNew) {
                changes.push(`保險方案變更：從 "${insuranceNameOld}" 變更為 "${$('#modal-ins_select option:selected').text().trim()}"`);
                changes.push(` >費用金額可能會變更，請注意!`);

            }
            // 比較付款方式
            let paymentMethodNew = $('#modal-method_select').val();
            if (paymentMethodOld !== paymentMethodNew) {
                changes.push(`付款方式變更：從 "${payment_replae(paymentMethodOld)}" 變更為 "${payment_replae(paymentMethodNew)}"`);
            }

            // 比較付款狀態
            let paymentStatusNew = $('#modal-payment_status_select option:selected').text();
            if (paymentStatusOld !== paymentStatusNew) {
                changes.push(`付款狀態變更：從 "${paymentStatusOld}" 變更為 "${paymentStatusNew}"`);
            }
            // 比較訂單狀態
            let orderStatusNew = $('#modal-status option:selected').val();
            if (orderStatusOld !== orderStatusNew) {
                changes.push(`訂單狀態變更：從 "${status_replace(orderStatusOld)}" 變更為 "${status_replace(orderStatusNew)}"`);
            }

            // 如果有變更，顯示確認視窗
            if (changes.length > 0) {
                // let changeSummary = changes.join('<br>');
                let changeSummary = changes.join('\n');
                if (confirm(`確定要儲存以下變更嗎？\n\n${changeSummary}`)) {
                    // 提交資料
                    const formData = {
                        _token: '{{ csrf_token() }}',
                        order_id: $('#modal-id').text(),
                        insurance_id: $('#modal-ins_select').val(),
                        order_status: $('#modal-status').val(),
                        payment_method: $('#modal-method_select').val(),
                        payment_status: $('#modal-payment_status_select').val(),
                        changes: changeSummary
                    };

                    $.ajax({
                        url: '{{route('admin.rental_update')}}',
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if(response['status']=="success"){
                                location.reload();
                            }else{
                                showToast(response['message'], 'error')
                            }
                        },
                        error: function (xhr) {
                            showToast('發送失敗', 'error')
                            console.log(xhr);
                        }
                    });
                }
            } else {
                showToast('沒有任何變更', 'error')
            }
        });
    </script>
@endsection
