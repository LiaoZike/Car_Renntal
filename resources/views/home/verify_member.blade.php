@section('title') 123．租車 | 帳號註冊 @endsection
@extends('home/blade/master')

@section('main_css')
    <style>
        .register-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        .register-image {
            flex: 1 1 50%;
            background: url('{{ asset('images/hero_bg.jpg') }}') center center/cover no-repeat;
            min-height: 300px;
        }

        .register-form {
            flex: 1 1 50%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .btn-disabled {
            pointer-events: none;
            background-color: gray !important;
            border-color: gray !important;
            color: white !important;
        }

        @media (max-width: 768px) {
            .register-card {
                flex-direction: column;
            }

            .register-image {
                flex: none;
                width: 100%;
                min-height: 200px;
            }

            .register-form {
                flex: none;
                width: 100%;
                padding: 20px;
            }
        }

        body{
            display: block;
            background: #5c5b5b;
        }
        header{
            background: white;
        }
        footer{
            display: none;
        }
        input.is-invalid {
            border-color: #dc3545 !important;
        }
        input.is-valid {
            border-color: #28a745 !important;
        }

    </style>
@endsection

@section('main_section')
    <div class="container register-container">
        <div class="register-card">
            <div class="register-image"></div>

            <div class="register-form">
                <h3 class="text-center mb-4">建立帳號</h3>

                <form action="{{ route('google.auth.verify_phone_post') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="gmail">您的電子信箱</label>
                        <div class="input-group">
                            <input style="background-color:#DDD !important;" type="text" class="form-control" name="gmail" value="{{ session('user_data')['email'] }}" readonly>
                        </div>
                    </div>

                    <label>輸入手機號碼和驗證碼</label>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="*手機號碼" value="{{old('phone')}}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="code" name="code" placeholder="*驗證碼" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" id="sendCodeBtn">取得驗證碼</button>
                            </div>
                        </div>
                        @if(session('code_error'))
                            <div id="codeError" style="color: red;">
                                {{ session('code_error') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="agreeCheckbox" name="agree" required>
                        <label class="form-check-label" for="agreeCheckbox">
                            *我已閱讀並同意 <a href="#" data-toggle="modal" data-target="#termsModal">服務條款</a>
                        </label>
                        @include('home/TermsofService')

                    </div>

                    <button type="submit" class="btn btn-primary btn-block">建立帳號</button>
                </form>

                <p class="mt-3 text-center">
                    已有帳號？<a href="{{route('google.auth.page')}}">立即登入</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('main_js')
    <script>
        let count = {{ $remaining }};
        const $btn = $('#sendCodeBtn');
        const $phoneInput = $('#phone');
        const $codeInput = $('#code');
        const $codeError = $('#codeError');
        const timeout = 60;
        const phonePattern = /^09\d{8}$/;

        // 更新手機欄位 class 樣式
        function updatePhoneClass(isValid) {
            $phoneInput
                .removeClass('is-valid is-invalid')
                .addClass(isValid ? 'is-valid' : 'is-invalid');
        }

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
        // 發送驗證碼，包含檢查手機格式
        function validateAndSendCode() {
            const phone = $phoneInput.val();
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
                    console.log(xhr);
                }
            });
        }

        // 初始化：如果還有剩餘秒數，繼續倒數
        if (count > 0) startCountdown();

        // 頁面加載時手機欄位驗證
        if ($phoneInput.val()) {
            updatePhoneClass(phonePattern.test($phoneInput.val()));
        }


        $phoneInput.on('input', () => {
            const isValid = phonePattern.test($phoneInput.val());
            updatePhoneClass(isValid);
        });

        // 驗證碼輸入時，自動清除錯誤提示
        $codeInput.on('input', () => {
            if ($codeError.length) $codeError.remove();
        });

        // 發送按鈕事件
        $btn.on('click', validateAndSendCode);
        $(document).ready(function (){
            $('#termsLink').on('click', function(event) {
                event.preventDefault();
                $('#termsModal').modal('show');
            });
        })
        document.getElementById('agreeButton').addEventListener('click', function() {
            document.getElementById('agreeCheckbox').checked = true;
        });
        document.getElementById('disagreeButton').addEventListener('click', function() {
            document.getElementById('agreeCheckbox').checked = false;
        });
    </script>


@endsection
