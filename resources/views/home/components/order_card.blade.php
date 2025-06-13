@php
    use Carbon\Carbon;

    // 1. 取得關鍵時間
    $now         = Carbon::now();
    $createdAt   = Carbon::parse($order->created_at);
    $startDate   = Carbon::parse($order->start_date)->startOfDay();  // 只取日期、00:00

    // 2. 計算各個「截點」
    $normoal_line = $startDate->copy()->subDays(2);    // 正常截止日期
//    dd($startDate);
    // 3. 判斷是否為「臨時訂單」＆「臨時訂單 1 天內可改」
    $isTemp      = ($createdAt->gt($normoal_line))&&($now->lt($startDate)); //(臨時訂單判斷)
//    $inTempHour  = $now->lt($createdAt->copy()->addDay());
    // 4. 計算「編輯截止時間」
    if ($now->lt($normoal_line)) { //(正常deadline)
        $deadLine = $normoal_line;
        $deadLine->subSecond(1);
    }
    elseif ($isTemp) {
        // 臨時訂單：取「建立後 1 天」與「取車日前一天 00:00」中較早者
        $deadLine = min(
            $createdAt->copy()->addMinutes(15),$startDate
        )->copy()->subSecond();
        $deadLine->subSecond(1);
    }
    else {
        $deadLine = null;
    }
    // 5. 最終判斷：未付款 && deadline 存在 && 現在 < deadline
    $canEdit = ($order->rental_status == 'pending' || $order->rental_status == 'active')
               && $order->payment_status == 0
               && $deadLine instanceof Carbon
               && $now->lt($deadLine);

    // 6. 若可編輯，計算剩餘時間並格式化
    if ($canEdit) {
        $seconds = $now->diffInSeconds($deadLine, false);
        if ($seconds >= 86400) { // 一天 = 86400 秒
            $d = floor($seconds / 86400);
            $h = floor(($seconds % 86400) / 3600);
            $timeText = "{$d} 天";
            if ($h > 0) {
                $timeText .= " {$h} 小時";
            }
        }  elseif ($seconds < 60) {
            $timeText = round($seconds)." 秒";
        } elseif ($seconds < 3600) {
            $m = round($seconds / 60); // 四捨五入分鐘
            $timeText = "{$m} 分";
        } else {
            $h = round($seconds / 3600, 1); // 小數點1位小時
            $timeText = "{$h} 小時";
        }
    }
@endphp


<div class="col-md-6 p-2">
    <div class="card shadow-sm {{ rentalCardBg($order->rental_status) }}">
        <div class="card-header d-flex justify-content-between align-items-center {{ rentalStatusBadge($order->rental_status) }}">
            <h5 class="mb-0 text-white">訂單編號 #{{ $order->rental_id }}</h5>
            @if ($canEdit)
            <button class="btn btn-sm btn-light" onclick="toggleEdit({{ $index }})">編輯</button>
            @endif
        </div>
        <div class="card-body">
            <div id="display_area_{{ $index }}">
                @if ($canEdit)
                    <div class="text-danger fw-bold">
                        請於時間內確認訂單：{{ $timeText }}（至 {{ $deadLine->format('Y/m/d H:i') }} 可編輯）
                    </div>
                @endif
                <div class="mb-3">
                    <strong>租借狀態：</strong>
                    <span class="badge {{ rentalStatusBadge($order->rental_status) }}" style="font-size: 18px !important;">
                        {{ rentalStatusText($order->rental_status) }}
                    </span>
                </div>
                <div class="text-center mb-3">
                    <img src="{{ $order->image_url }}" alt="{{ $order->brand }} {{ $order->model_name }}" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                </div>
                <p class="mb-1"><strong>車輛：</strong>{{ $order->brand }} {{ $order->model_name }}&ensp;/&ensp;{{$order->engine_cc/1000}}L</p>
                <p class="mb-1"><strong>取車：</strong>{{ $order->pickup_location_name }}</p>
                <p class="mb-1"><strong>還車：</strong>{{ $order->drop_location_name }}</p>
                <p class="mb-1"><strong>租期：</strong>{{ \Carbon\Carbon::parse($order->start_date)->format('Y/m/d H:i') }} - {{ \Carbon\Carbon::parse($order->end_date)->format('Y/m/d H:i') }}</p>
                <p class="mb-1"><strong>金額：</strong>NT$ {{ number_format($order->amount, 0) }}<br>&ensp;>{{$order->show_account_calc}}</p>
                <p class="mb-1"><strong>付款方式：</strong>{{ ucfirst($order->method) }}</p>
                <p class="mb-1"><strong>付款狀態：</strong>{{ $order->payment_status ? '已付款' : '未付款' }}</p>
                <p class="mb-1"><strong>保險方案：</strong>{{ $order->ins_name }}</p>

                <button class="btn btn-sm btn-link p-0 mt-2" data-bs-toggle="collapse" data-bs-target="#coverage_{{ $index }}">查看保險內容</button>
                <div class="collapse mt-2" id="coverage_{{ $index }}">
                    <div class="border rounded p-2 bg-light">
                        <small>{!! nl2br(e($order->coverage)) !!}</small>
                    </div>
                </div>
            </div>

            {{-- 編輯區 --}}
            <div id="edit_area_{{ $index }}" class="d-none mt-3">
                <label class="form-label fw-bold">選擇保險方案</label>
                <div class="row row-cols-1 row-cols-lg-3 g-3">
                    @foreach($insurances as $insurance)
                        @php
                            $isSelected = $order->ins_name === $insurance->ins_name;
                        @endphp
                        <div class="col-4 mb-1">
                            <div class="card h-100 {{ $isSelected ? 'border-primary shadow-sm' : 'border-light' }}"
                                 data-group="group_{{ $order->rental_id }}">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="insurance_{{ $order->rental_id }}"
                                            id="insurance_{{ $index }}_{{ $loop->index }}"
                                            value="{{ $insurance->insurance_id }}" {{-- ✅ 傳送用 ID --}}
                                            {{ $isSelected ? 'checked' : '' }}
                                            data-group="group_{{ $order->rental_id }}"
                                            style="cursor: pointer"
                                        />
                                        <label class="form-check-label fs-5"
                                               for="insurance_{{ $index }}_{{ $loop->index }}"
                                               style="user-select: none;cursor: pointer">
                                            {{ $insurance->ins_name }}<br>(+NT$ {{ $insurance->ins_fee }} 元) {{-- ✅ 顯示用名稱 --}}
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small text-muted" style="white-space: pre-line; max-height: 15em; overflow-y: auto;">
                                        {!! nl2br(e($insurance->coverage)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-end mt-3 gap-2">
                    <button
                        onclick="saveEdit({{ $index }}, {{ $order->rental_id }})"
                        class="btn btn-success btn-sm"
                    ><i class="fa fa-save" aria-hidden="true"></i>&ensp;儲存變更</button>
                    <button
                        onclick="cancelEdit({{ $index }})"
                        class="btn btn-secondary btn-sm ml-2"
                    ><i class="fa fa-close" aria-hidden="true"></i>&ensp;取消編輯</button>
                </div>
                {{-- 🔴 取消訂單另起一行，明顯分開 --}}
                <div class="text-end mt-3">
                    <button
                        onclick="cancelOrder({{ $order->rental_id }})"
                        class="btn btn-outline-danger btn-sm"
                    ><i class="fa fa-trash" aria-hidden="true"></i>&ensp;取消該筆訂單</button>
                </div>
            </div>
        </div>
    </div>
</div>
