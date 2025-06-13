@section('title') 123．租車 | 會員中心 @endsection
@extends('home/blade/master')

@section('source_css')
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">
    <link rel="stylesheet" href="{{asset('dist/css/daterangepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('dist/css/nouislider.css')}}"/>
@endsection
@section('source_js')
    <script src="{{asset('dist/js/moment.min.js')}}"></script>
    <script src="{{asset('dist/js/daterangepicker.min.js')}}" defer></script>
    <script src="{{asset('dist/js/nouislider.min.js')}}" defer></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
{{--    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>--}}
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@section('main_section')
    <div class="container my-5 pt-5">
        <h2 class="mb-4 fw-bold">我的聯絡資訊</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card shadow-sm border-light p-4 h-100">
                    <h5 class="text-center">Email</h5>
                    <p class="text-center text-muted">{{ session('user_data.email') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="card shadow-sm border-light p-4 h-100">
                    <h5 class="text-center">電話</h5>
                    <p class="text-center text-muted">{{ session('user_data.phone') }}</p>
                    <div class="text-center">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPhoneModal">
                            <i class="fa fa-edit" aria-hidden="true"></i>&ensp;修改電話
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editPhoneModal" tabindex="-1" aria-labelledby="editPhoneModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="update-phone-form" method="POST" action="{{ route('google.auth.update_phone')}}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPhoneModalLabel">修改電話</h5>
                                <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="關閉"><span aria-hidden="true">&times;</span></button>                               </button>
                            </div>
                            <div class="modal-body">
                                <label for="phone">新電話號碼</label>
                                <input type="text" class="form-control" name="phone" id="phone" required pattern="^09\d{8}$" placeholder="請輸入 09 開頭的手機號碼">

                                <div class="form-group mt-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" name="code" placeholder="*驗證碼" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" id="sendCodeBtn">取得驗證碼</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">確認修改</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <h2 class="mt-4 fw-bold">我的租車訂單</h2>
        {{------------------------------------------ 顯示租用訂單 ------------------------------------------}}
        {{-- 租用中 --}}
        <h4>租用中</h4>
        @if(isset($ordersGrouped['active']) && $ordersGrouped['active']->isNotEmpty())
            <div class="row g-4">
                @foreach($ordersGrouped['active'] as $index => $order)
                    @include('home.components.order_card', ['order' => $order])
                @endforeach
            </div>
        @else
            <div class="alert alert-info">目前沒有租用中的訂單。</div>
        @endif

        {{-- 等待中 --}}
        <h4>等待審核</h4>
        @if(isset($ordersGrouped['pending']) && $ordersGrouped['pending']->isNotEmpty())
            <div class="row g-4">
                @foreach($ordersGrouped['pending'] as $index => $order)
                    @include('home.components.order_card', ['order' => $order])
                @endforeach
            </div>
        @else
            <div class="alert alert-info">目前沒有等待中的訂單。</div>
        @endif

        {{-- 已完成 --}}
        <h4>已完成</h4>
        @if(isset($ordersGrouped['completed']) && $ordersGrouped['completed']->isNotEmpty())
            <div class="row g-4">
                @foreach($ordersGrouped['completed'] as $index => $order)
                    @include('home.components.order_card', ['order' => $order])
                @endforeach
            </div>
        @else
            <div class="alert alert-info">目前沒有已完成的訂單。</div>
        @endif

        {{-- 已取消 --}}
        <h4>已取消</h4>
        @if((isset($ordersGrouped['cancelled']) && $ordersGrouped['cancelled']->isNotEmpty()) || (isset($ordersGrouped['reject']) && $ordersGrouped['reject']->isNotEmpty()))
            <div class="row g-4">
                {{-- 顯示已取消的訂單 --}}
                @if(isset($ordersGrouped['cancelled']) && $ordersGrouped['cancelled']->isNotEmpty())
                    @foreach($ordersGrouped['cancelled'] as $index => $order)
                        @include('home.components.order_card', ['order' => $order])
                    @endforeach
                @endif

                {{-- 顯示其他狀態的訂單 --}}
                @if(isset($ordersGrouped['reject']) && $ordersGrouped['reject']->isNotEmpty())
                    @foreach($ordersGrouped['reject'] as $index => $order)
                        @include('home.components.order_card', ['order' => $order])
                    @endforeach
                @endif
            </div>
        @else
            <div class="alert alert-info">目前沒有已取消或其他狀態的訂單。</div>
        @endif
    </div>

    <!-- 取消訂單的 Modal -->
    <div class="modal fade position-fixed" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">
                        取消訂單
                        <span id="cancelOrderIdText" class="fw-bold"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    確定要取消此訂單嗎？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">關閉</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelOrder">確定取消訂單</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('main_css')
    <style>
        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1); /* 淺黃 */
        }

        .bg-primary-light {
            background-color: rgba(0, 123, 255, 0.1); /* 淺藍 */
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1); /* 淺綠 */
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1); /* 淺紅 */
        }
        .btn-disabled {
            pointer-events: none;
            background-color: gray !important;
            border-color: gray !important;
            color: white !important;
        }
    </style>
@endsection
@section('main_js')
    @php
        function rentalStatusBadge($status) {
            switch ($status) {
                case 'pending': return 'bg-warning text-dark';  // 黃
                case 'active': return 'bg-primary';             // 藍
                case 'completed': return 'bg-success';          // 綠
                case 'cancelled': return 'bg-danger';           // 紅
                case 'reject': return 'bg-danger';           // 紅
                default: return 'bg-secondary';                 // 灰
            }
        }
        function rentalCardBg($status) {
            switch ($status) {
                case 'pending': return 'bg-warning-light'; // 淺黃
                case 'active': return 'bg-primary-light';  // 淺藍
                case 'completed': return 'bg-success-light'; // 淺綠
                case 'cancelled':
                case 'reject': return 'bg-danger-light';  // 淺紅
                default: return ''; // 其他情況
            }
        }

        function rentalStatusText($status) {
            switch ($status) {
                case 'pending': return '待處理';
                case 'active': return '租用中';
                case 'completed': return '已完成';
                case 'cancelled': return '已取消';
                case 'reject': return '拒絕租用';
                default: return '未知狀態';
            }
        }
    @endphp

    <script>
        {{--------------------------------------------- 訂單刪除管理 ---------------------------------------------}}
        let currentRentalId = null;

        // 開啟編輯模式(動作)
        function toggleEdit(index) {
            document.getElementById('display_area_' + index).classList.add('d-none');
            document.getElementById('edit_area_' + index).classList.remove('d-none');
        }
        // 關閉編輯模式(動作)
        function cancelEdit(index) {
            document.getElementById('edit_area_' + index).classList.add('d-none');
            document.getElementById('display_area_' + index).classList.remove('d-none');
        }
        // 刪除訂單按鈕(動作)
        function cancelOrder(rentalId) {
            currentRentalId = rentalId;
            document.getElementById('cancelOrderIdText').textContent = '(編號#' + rentalId+')';
            const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            cancelOrderModal.show();
        }
        // 確定刪除訂單按鈕
        $(document).ready(function () {
            $('#confirmCancelOrder').on('click', function() {
                if (!currentRentalId) return;
                console.log(currentRentalId);
                $.ajax({
                    url: '{{route('rental_trash')}}',
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        _token: '{{ csrf_token() }}',
                        RentalId:currentRentalId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response['status']=="success"){
                            location.reload();
                        }else{
                            showToast(response['message'], 'error')
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('取消失敗，請稍後再試', 'error')
                        console.log(response['message']);
                    }
                });
            });
        })
        {{------------------------------------------------------------------------------------------------------}}
        {{-------------------------------------------- 訂單編輯保險選擇 -------------------------------------------}}
        $(document).ready(function () {
            document.querySelectorAll('input[type="radio"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    const group = this.dataset.group;

                    // 找出同一 group 的卡片來清除樣式
                    document.querySelectorAll('.card[data-group="' + group + '"]').forEach(function(card) {
                        card.classList.remove('border-primary');
                        card.classList.add('border-light');
                    });

                    // 加上被選中的樣式
                    if (this.checked) {
                        this.closest('.card').classList.add('border-primary');
                        this.closest('.card').classList.remove('border-light');
                    }
                });
            });
        });
        {{-------------------------------------------------------------------------------------------------------}}
        {{---------------------------------------------- 訂單編輯儲存 ---------------------------------------------}}
        function saveEdit(index, rentalId) {
            const selectedInsurance = document.querySelector(`input[name="insurance_${rentalId}"]:checked`);
            if (!selectedInsurance) {
                showToast('請選擇保險方案', 'error')
                return;
            }

            $.ajax({
                url: '{{route('rental_edit')}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data:{
                    _token: '{{ csrf_token() }}',
                    rentalId: rentalId,
                    insurance_id:selectedInsurance.value,
                },
                success: function (response) {
                    if(response['status']=="success"){
                        location.reload();
                    } else {
                        showToast(response['message'], 'error')
                    }
                },
                error: function (xhr, status, error) {
                    showToast('修改失敗，請稍後再試', 'error')
                    console.log(response['message']);
                }
            });
        }
        {{-------------------------------------------------------------------------------------------------------}}

        {{---------------------------------------------- 修改電話號碼 ---------------------------------------------}}

        let count = {{ $remaining }};
        const $btn = $('#sendCodeBtn');
        const $phoneInput = $('#phone');
        const $codeInput = $('#code');
        const $codeError = $('#codeError');
        const timeout = 60;
        const phonePattern = /^09\d{8}$/;
        // 倒數計時
        function startCountdown() {
            if (count <= 0) return;
            $btn.addClass('btn-disabled').text(`重新發送驗證碼(${count})`);
            let timer = setInterval(function () {
                count--;
                $btn.text(`重新發送驗證碼(${count})`);
                if (count <= 0) {
                    clearInterval(timer);
                    $btn.removeClass('btn-disabled').text('發送驗證碼');
                    count = 60;
                }
            }, 1000);
        }

        if (count > 0) startCountdown(); // 初始化：如果還有剩餘秒數，繼續倒數

        function validateAndSendCode() {
            const phone = $phoneInput.val()
            const isValid = phonePattern.test(phone);
            updatePhoneClass(isValid);

            if (!isValid) {
                showToast('請輸入正確的手機號碼（09開頭，共10碼）', 'error')
                return;
            }

            $.ajax({
                url: '{{ route('google.auth.sendcode') }}',
                type: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: JSON.stringify({ phone }),
                success: data => {
                    switch (data.status) {
                        case 'success':
                            count = timeout;
                            showToast('驗證碼已發送', 'info')
                            startCountdown();
                            break;
                        case 'session_timeout':
                            window.location = "{{ route('home') }}";
                            break;
                        case 'error':
                            showToast(data.message, 'error')
                            break;
                        default:
                            showToast("發送失敗", 'error')
                            console.log(data.message);
                    }
                },
                error: xhr => {
                    const errors = xhr.responseJSON?.errors?.phone?.[0];
                    showToast('發送失敗', 'error')
                    console.log(data.message);
                }
            });
        }
        $btn.on('click', validateAndSendCode)

        // 更新手機欄位 class 樣式
        function updatePhoneClass(isValid) {
            $phoneInput
                .removeClass('is-valid is-invalid')
                .addClass(isValid ? 'is-valid' : 'is-invalid');
        }
        $phoneInput.on('input', () => {
            const isValid = phonePattern.test($phoneInput.val());
            updatePhoneClass(isValid);
        });
        $codeInput.on('input', () => {// 驗證碼輸入時，自動清除錯誤提示
            if ($codeError.length) $codeError.remove();
        });

        //更新電話號碼事件
        $('#update-phone-form').on('submit', function (e) {
            e.preventDefault(); // 阻止表單預設提交
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(), // 序列化表單資料
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val() // 傳送 CSRF token
                },
                success: function (response) {
                    if(response['status']=="success"){
                        location.reload();
                    } else {
                        showToast(response['message'], 'error')
                    }
                },
                error: function (xhr, status, error) {
                    showToast('修改失敗，請稍後再試', 'error')
                    console.log(response['message']);
                }
            });
        });
        {{-------------------------------------------------------------------------------------------------------}}
        function formatMethod(method) {
            switch(method) {
                case 'cash': return '現金';
                case 'credit_card': return '信用卡';
                case 'line_pay': return 'Line Pay';
                case 'bank_transfer': return '銀行轉帳';
                default: return method;
            }
        }
    </script>
@endsection
