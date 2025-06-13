@section('title') 123．租車 | 查詢 @endsection
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

@section('main_section')
    <div class="container mb-5" style="margin-top:200px;">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-10">
                <h2 class="section-heading mb-2"><strong>&ensp;篩選條件</strong></h2>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-10">
                <form class="trip-form" id="search_form" action="{{route('rental_search_post')}}" method="POST">
                    @csrf()
                    <div class="container">
                        <div class="form-group">
                            <label for="location">取還車站點</label>
                            <select class="form-control" id="location" name="location">
                                @if(!empty($locations))
                                @foreach($locations as $location)
                                <option value="{{$location->loc_id}}">{{$location->loc_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="empty_click">選擇日期區間：</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="noDateLimit" name="noDateLimit" {{ old('noDateLimit', $noDateLimit ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="noDateLimit">不限日期</label>
                            </div>
                            <input type="text" name="daterange" id="daterange" class="form-control" />
                        </div>
                        <div class="form-group" id="SelectTimeForm">
                            <label for="pickupTime">取車時間</label>
                            <input type="time" id="pickupTime" name="pickupTime" value="{{ old('pickupTime', $pickupTime ?? []) ? $pickupTime : "08:00" }}">&ensp;
                            <label for="returnTime">還車時間</label>
                            <input type="time" id="returnTime" name="returnTime" value="{{ old('returnTime', $returnTime ?? []) ? $returnTime : "17:00" }}">
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label>目前選擇：</label><pre id="result"></pre>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <div class="row">
                                {{-- 車種 --}}
                                <div class="col-md-6 mb-6 type_select_div">
                                    <strong>車種：</strong><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="car_type[]" value="Any" id="car_type_any"
                                            {{ in_array('Any', old('car_type', $selectedTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="car_type_any">不限</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="car_type[]" value="Compact" id="car_type_compact"
                                            {{ in_array('Compact', old('car_type', $selectedTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="car_type_compact">小型車</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="car_type[]" value="Sedan" id="car_type_sedan"
                                            {{ in_array('Sedan', old('car_type', $selectedTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="car_type_sedan">中大型房車</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="car_type[]" value="SUV" id="car_type_suv"
                                            {{ in_array('SUV', old('car_type', $selectedTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="car_type_suv">SUV</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="car_type[]" value="MPV" id="car_type_mpv"
                                            {{ in_array('MPV', old('car_type', $selectedTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="car_type_mpv">MPV</label>
                                    </div>
                                </div>
                                {{-- 燃料類型 --}}
                                <div class="col-md-6 mb-6 type_select_div">
                                    <strong>燃油類型：</strong><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="fuel_type[]" value="Any" id="fuel_type_any"
                                            {{ in_array('Any', old('fuel_type', $selectedFuelTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fuel_type_any">不限</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="fuel_type[]" value="Gasoline" id="fuel_type_gasoline"
                                            {{ in_array('Gasoline', old('fuel_type', $selectedFuelTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fuel_type_gasoline">汽油</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="fuel_type[]" value="Electric" id="fuel_type_electric"
                                            {{ in_array('Electric', old('fuel_type', $selectedFuelTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fuel_type_electric">電動</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="fuel_type[]" value="Hybrid" id="fuel_type_hybrid"
                                            {{ in_array('Hybrid', old('fuel_type', $selectedFuelTypes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fuel_type_hybrid">混合動力</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 乘載人數 -->
                        <div class="form-group">
                            <label for="seats">乘載人數：</label>
                            <select name="seats" id="seats" class="form-control">
                                <option value="0" {{ ($seats ?? '') == '0' ? 'selected' : '' }}>不限</option>
                                <option value="2" {{ ($seats ?? '') == '2' ? 'selected' : '' }}>2人座</option>
                                <option value="4" {{ ($seats ?? '') == '4' ? 'selected' : '' }}>4人座</option>
                                <option value="5" {{ ($seats ?? '') == '5' ? 'selected' : '' }}>5人座</option>
                                <option value="7" {{ ($seats ?? '') == '7' ? 'selected' : '' }}>7人座</option>
                                <option value="9" {{ ($seats ?? '') == '9' ? 'selected' : '' }}>9人座</option>
                            </select>
                        </div>

                        <!-- 價格篩選 -->
                        <div class="form-group p-2" style="border-radius: 5px;background-color: #d4e7f3">
                            <label class="form-label">每日租金範圍：</label>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <input type="number" class="form-control" id="min_value" name="min_value" step="100" style="max-width: 130px;" value="{{ old('min_value', $min_value ?? []) ? $min_value : 0 }}">
                                <span>&ensp;-&ensp;</span>
                                <input type="number" class="form-control" id="max_value" name="max_value" step="100" style="max-width: 130px;" value="{{ old('max_value', $max_value ?? []) ? $max_value : 6000 }}">
                            </div>
                            <div id="slider" class="ml-3 mr-3"></div>
                        </div>

                    </div>
                     <div class="mb-3 mb-md-0 col-md-3">
                         <button type="submit" class="btn btn-primary p-3">
                             <i class="fa fa-search" aria-hidden="true"></i>&ensp;查詢可租車輛
                         </button>
                     </div>

                </form>

            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="section-heading"><strong>可租用車輛</strong></h2>
                </div>
            </div>

            <div class="row mt-5">
                @if(empty($cars) || $cars->isEmpty())
                    <p>抱歉，目前您的篩選沒有可租用車輛，嘗試其它日期。</p>
                @else
                    @php
                        // 先依 car_type 分組並排序
                        $carsByType = $cars->groupBy('car_type')
                            ->sortBy(function ($typeCars, $type) {
                              return preg_match('/[\x{4e00}-\x{9fff}]/u', $type)
                                     ? $type
                                     : strtolower($type);
                            })->reverse();
                    @endphp

                    @foreach($carsByType as $type => $typeCars)
                        <div class="container mb-4">
                            <h2 class="mb-5 text-center py-1"
                                style="background-color: #ffeebc; border-bottom: 2px grey solid">
                                {{ $type }}
                            </h2>

                            <div class="row">
                                @php
                                    // 同車種裡，再依 model_id 分組
                                    $carsByModel = $typeCars->groupBy('model_id');
                                @endphp

                                @foreach($carsByModel as $modelId => $group)
                                    @foreach($group as $idx => $car)
                                        <div class="col-md-6 col-lg-4 mb-2 model-{{ $modelId }} h-100"
                                             style="display: {{ $idx === 0 ? 'block' : 'none' }};">
                                            <div class="listing d-block align-items-stretch pb-0">
                                                <div class="listing-img h-100 mr-4">
                                                    <img src="{{ asset($car->image_url) }}" alt="Image" class="img-fluid">
                                                </div>
                                                <div class="listing-contents h-100">
                                                    <h3>{{ $car->brand }} {{ $car->model_name }} /{{($car->engine_cc==0)?"":$car->engine_cc/1000 . "L"}}</h3>
                                                    {{$car->transmission}}
                                                    <div class="rent-price">
                                                        <strong>NT$ {{ $car->daily_fee }}</strong><span class="mx-1">/</span>day
                                                    </div>
                                                    <div class="d-block d-md-flex mb-3 border-bottom pb-3">
                                                        <div class="listing-feature pr-4">
                                                            <span class="caption">類型:</span><br>
                                                            <span>{{ $car->car_type }}</span>
                                                        </div>
                                                        <div class="listing-feature pr-4">
                                                            <span class="caption">可乘坐人數:</span><br>
                                                            <span class="number">{{ $car->seat_num }}</span>
                                                        </div>
                                                        <div class="listing-feature pr-4">
                                                            <span class="caption">燃油:</span><br>
                                                            <span>{{ $car->fuel_type }}</span>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary btn-check-availability d-block mx-auto"
                                                            data-car-id="{{ $car->car_id }}"
                                                            data-car-brand="{{ $car->brand }}"
                                                            data-car-model="{{ $car->model_name }}"
                                                            data-car-detail="({{ $car->car_type }} - {{ $car->seat_num }}人座) 燃油方式:{{ $car->fuel_type }}"
                                                            data-car-fee="{{ $car->daily_fee }}"
                                                            data-car-latefee="{{ $car->late_fee }}"
                                                            data-car-image="{{ asset($car->image_url) }}">
                                                        租用 / 查詢可租借時段
                                                    </button>
                                                    {{-- 顯示更多此車款按鈕 --}}
                                                   <div class="w-100 text-center mt-1">
                                                       @if($group->count() > 1)
                                                       <button class="btn btn-link btn-sm btn-show-more" type="button" data-model-id="{{ $modelId }}" >
                                                           &lt;顯示更多此車款（還有 {{ $group->count() - 1 }} 輛)&gt;
                                                       </button>
                                                       @else
                                                           <button class="btn btn-link btn-sm btn-show-more" type="button" data-model-id="{{ $modelId }}" ></button>
                                                       @endif
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

{{--            <div class="row mt-5">--}}
{{--                --}}{{--                @foreach($cars as $car)--}}
{{--                @if(empty($cars) || sizeof($cars)==0)--}}
{{--                    <p>抱歉，目前您的篩選沒有可租用車輛，嘗試其它日期。</p>--}}
{{--                @else--}}
{{--                @php--}}
{{--                    $carsByType = $cars->groupBy('car_type');--}}
{{--                    $carsByType = $carsByType->sortBy(function ($typeCars, $type) {--}}
{{--                        if (preg_match('/[\x{4e00}-\x{9fff}]/u', $type)) {--}}
{{--                            return $type;  // 中文保持正常排序--}}
{{--                        } else {--}}
{{--                            return strtolower($type);  // 英文倒序排序--}}
{{--                        }--}}
{{--                    })->reverse();  // 反向排序英文--}}
{{--                @endphp--}}
{{--                @foreach($carsByType as $type => $typeCars)--}}
{{--                <div class="container mb-2">--}}
{{--                    <h2 class="mb-5 text-center py-1" style="background-color: #ffeebc; border-bottom: 2px grey solid">{{ $type }}</h2>--}}
{{--                    <div class="row">--}}
{{--                        @foreach($typeCars as $car)--}}
{{--                <div class="col-md-6 col-lg-4 mb-4">--}}
{{--                    <div class="listing d-block  align-items-stretch">--}}
{{--                        <div class="listing-img h-100 mr-4">--}}
{{--                            <img src="{{asset($car->image_url)}}" alt="Image" class="img-fluid">--}}
{{--                        </div>--}}
{{--                        <div class="listing-contents h-100">--}}
{{--                            <h3>{{$car->brand}} {{$car->model_name}} /{{$car->engine_cc/1000}}L</h3>--}}
{{--                            <div class="rent-price">--}}
{{--                                <strong>NT$ {{$car->daily_fee}}</strong><span class="mx-1">/</span>day--}}
{{--                            </div>--}}
{{--                            <div class="d-block d-md-flex mb-3 border-bottom pb-3">--}}
{{--                                <div class="listing-feature pr-4">--}}
{{--                                    <span class="caption">類型:</span><br>--}}
{{--                                    <span class="">{{$car->car_type}}</span>--}}
{{--                                </div>--}}
{{--                                <div class="listing-feature pr-4">--}}
{{--                                    <span class="caption">可乘坐人數:</span><br>--}}
{{--                                    <span class="number">{{$car->seat_num}}</span>--}}
{{--                                </div>--}}
{{--                                <div class="listing-feature pr-4">--}}
{{--                                    <span class="caption">燃油:</span><br>--}}
{{--                                    <span class="">{{$car->fuel_type}}</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            +"engine_cc": 1800--}}
{{--                            +"transmission": "自排"--}}
{{--                            <div>--}}
{{--                                <p>{{$car->notes}}</p>--}}
{{--                                <p>--}}
{{--                                    <!-- 這是每個車輛的按鈕 -->--}}
{{--                                    <button class="btn btn-primary btn-check-availability"--}}
{{--                                            data-car-id="{{ $car->car_id }}"--}}
{{--                                            data-car-brand="{{ $car->brand }}"--}}
{{--                                            data-car-model="{{ $car->model_name }}"--}}
{{--                                            data-car-detail="({{ $car->car_type }} - {{$car->seat_num}}人座) 燃油方式:{{$car->fuel_type}}"--}}
{{--                                            data-car-fee="{{$car->daily_fee}}"--}}
{{--                                            data-car-latefee="{{$car->late_fee}}"--}}
{{--                                            data-car-image="{{asset($car->image_url)}}">--}}
{{--                                        租用 / 查詢可租借時段--}}
{{--                                    </button>--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}

{{--            </div>--}}
        </div>
    </div>
@endsection
@section('main_css')
    <style>
        .img-fluid {
            width: 300px;
            height: 180px;
        }
        .type_select_div{
            user-select: none;
        }
        .type_select_div label,.type_select_div input{
            cursor: pointer;
        }
        .noUi-connect{
            background: #0a58ca;
        }
        .daterangepicker td.off.disabled {
            background-color: rgba(177, 177, 177, 0.56) !important;
        }
        .daterangepicker td.off.disabled:hover{
            color:#999 !important;
            background-color: rgba(177, 177, 177, 0.56) !important;
        }
        /*#model_date{*/
        /*    display: block;*/
        /*    position: relative;*/
        /*}*/
        #model_date .daterangepicker{
            display: block !important;/
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 9999;
        }
        /*#model_date .cancelBtn,#model_date .btn-success, #model_date .daterangepicker_input{*/
        /*    display: none !important;*/
        /*}*/
        #model_date .daterangepicker_input{
            display: none !important;
        }
        #model_date{
            background-color: #f3ecce;
            /*display: block;*/
        }
        #daterange2 {
            border:2px solid black;
        }
        .insurance-card.selected {
            border: 2px solid #0d6efd;
            background-color: #eaf1ff;
        }
        .modal .card-body{
            cursor: pointer;
        }
        #main-car-image{
            border: solid 1px black;
        }

    </style>
@endsection
@section('second_section')
<!-- 共用 Modal -->
<div class="modal fade position-fixed" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body trip-form">
                <!-- Step 1 -->
                <div class="step step-1">
                    <div id="car-info">載入中...</div>

                    <div class="form-group">
                        <h6><i class="fa fa-calendar" aria-hidden="true"></i>&ensp;租車日期：</h6>
                        <input type="text" name="daterange2" id="daterange2" class="form-control" />
                        <div id="model_date" ></div>
                    </div>
                    <div class="form-group">
                        <label for="pickupTime2"><b>取車時間</b></label>
                        <input type="time" id="pickupTime2" value="{{ old('pickupTime', $pickupTime ?? []) ? $pickupTime : "08:00" }}">
                        <label for="returnTime2"><b>還車時間</b></label>
                        <input type="time" id="returnTime2" value="{{ old('returnTime', $returnTime ?? []) ? $returnTime : "17:00" }}">
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step step-2 d-none">
                    @if(session()->has('user_data') && session('user_data')['active'])
                    <h5>會員資訊確認</h5>
                    <h6><i class="fa fa-calendar" aria-hidden="true"></i>&ensp;信箱：</h6>
                    <input type="email" class="form-control mb-2" disabled value="{{session('user_data')['email']}}">
                    <h6><i class="fa fa-phone" aria-hidden="true"></i>&ensp;電話：</h6>
                    <input type="tel" class="form-control mb-2" disabled value="{{session('user_data')['phone']}}">
                    @else
                    <h5>你目前還未登入帳號</h5>
                    <p><b>請先登入以完成租車流程</b></p>
                    @endif
                </div>

                <!-- Step 3 -->
                <div class="step step-3 d-none">
                    <h5>保險方案</h5>
                    <h6>詳細保險內容請參照<a href="{{route('notice')}}" target="_blank">注意事項</a></h6>
                    @foreach($insurances as $insurance)
                        <div class="col-md-12 mb-3">
                            <div class="card insurance-card h-100" data-id="{{ $insurance->insurance_id }}" data-fee="{{ $insurance->ins_fee }}">
                                <div class="card-body">
                                    <div class="form-check pl-0">
                                        <input class="form-check-input d-none" type="radio" name="insurance_id" id="insurance_{{ $insurance->insurance_id }}" value="{{ $insurance->insurance_id }}">
                                        <label class="fw-bold fs-5" for="insurance_{{ $insurance->insurance_id }}" style="cursor: pointer;">
                                            {{ $insurance->ins_name }}（+NT$ {{ number_format($insurance->ins_fee, 0) }} 元/每日）
                                        </label>
                                    </div>
                                    <p class="mt-2 text-muted small">{!! nl2br(e($insurance->coverage)) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Step 4 -->
                <div class="step step-4 d-none">
                    <h5 class="mb-4"><strong>預約總覽確認</strong></h5>
                    <div class="summary-item mb-3">
                        <strong>車型：</strong> <span id="summary_car_model">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <img id="main-car-image" src="" alt="Car Image" class="img-fluid">
                    </div>
                    <div class="summary-item mb-3">
                        <strong>會員Email：</strong> <span id="summary_email">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>會員電話：</strong> <span id="summary_phone">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>取車日期時間：</strong> <span id="summary_pickup">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>還車日期時間：</strong> <span id="summary_return">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>保險方案：</strong> <span id="summary_insurance">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>總租金：</strong> <span id="summary_cost">載入中...</span>
                    </div>
                    <div class="summary-item mb-3">
                        <strong>若逾期未歸還車輛：</strong> 每小時收取NT$ <span id="summary_latefee">載入中...</span>元
                    </div>
                </div>
            </div>

            <!-- Modal Footer 切換按鈕 -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary prev-step d-none">上一步</button>
                <button type="button" class="btn btn-primary next-step">下一步</button>
                <button type="submit" class="btn btn-success submit-form d-none">送出預約</button>
            </div>
        </div>
    </div>
</div>
<!-- END -->
@endsection
@section('main_js')
    <script>

        @if(isset($first_show) && $first_show==true)
        document.getElementById('search_form').submit();
        @endif


        let conflict = false,isTimeValid = true;
        let carId,carBrand,carModel,cardetail,carfee,carimage,carlatefee
        let selectedInsurancePlan = {
            name: '',
            coverage: ''
        };
        let insurance_fee
        let currentStep = 1;
        let unavailableDates; //不可租用日期

        $(document).ready(function () {
            // ---------------------------
            // 日期區間選擇器初始化與更新
            // ---------------------------
            $('#daterange').daterangepicker({
                @if(!empty($daterange))
                startDate: "{{$daterange}}}".split(' - ')[0],
                endDate: "{{$daterange}}}".split(' - ')[1],
                @else
                startDate: moment().add(1, 'days'),
                endDate: moment().add(3, 'days'),
                @endif
                minDate:  moment().add(1, 'days'),
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: "確認",
                    cancelLabel: "取消",
                    fromLabel: "從",
                    toLabel: "到",
                    customRangeLabel: "自訂範圍"
                }
            }, function (start, end) {
                updateResult(start, end); // 當選擇日期時更新結果
            });
            // 當時間輸入欄改變時更新結果
            $('#pickupTime, #returnTime').on('change', function () {
                updateResult();
            });

            function updateResult(startDate = null, endDate = null) {
                let dateRange = $('#daterange').data('daterangepicker');

                let pickupTime = $('#pickupTime').val() || '未選取';
                let returnTime = $('#returnTime').val() || '未選取';

                let startText = (startDate || dateRange.startDate).format('YYYY-MM-DD') + " " + pickupTime;
                let endText = (endDate || dateRange.endDate).format('YYYY-MM-DD') + " " + returnTime;

                let resultText = `起始日期: ${startText}\n結束日期: ${endText}`;
                $('#result').text(resultText);
            }

            // ---------------------------
            // 車型選擇邏輯
            // ---------------------------
            $('#car_type_any').on('change', function () {
                if ($(this).is(':checked')) {
                    $('input[name="car_type[]"]').not(this).prop('checked', false);
                }
            });

            $('input[name="car_type[]"]').not('#car_type_any').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#car_type_any').prop('checked', false);
                }
            });

            // ---------------------------
            // 燃料類型選擇邏輯
            // ---------------------------
            $('#fuel_type_any').on('change', function () {
                if ($(this).is(':checked')) {
                    $('input[name="fuel_type[]"]').not(this).prop('checked', false);
                }
            });

            $('input[name="fuel_type[]"]').not('#fuel_type_any').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#fuel_type_any').prop('checked', false);
                }
            });

            // ---------------------------
            // 金額區間滑塊初始化
            // ---------------------------
            var slider = document.getElementById('slider');
            var minInput = $('#min_value');
            var maxInput = $('#max_value');
            if (slider) {
                noUiSlider.create(slider, {
                    start: [{{ old('min_value', $min_value ?? []) ? $min_value : 0 }}, {{ old('max_value', $max_value ?? []) ? $max_value : 6000 }}],
                    connect: true,
                    step: 100, // 每次移動的最小單位為 100
                    range: {
                        'min': 0,
                        'max': 10000
                    },
                    format: {
                        from: function(value) {
                            return parseInt(value);
                        },
                        to: function(value) {
                            return parseInt(value);
                        }
                    }

                });
                // 滑桿 → input 顯示
                slider.noUiSlider.on('update', function (values, handle) {
                    var value = Math.round(values[handle]);
                    if (handle === 0) {
                        minInput.val(value);
                    } else {
                        maxInput.val(value);
                    }
                });
                // input → 滑桿更新
                minInput.on('change', function () {
                    slider.noUiSlider.set([this.value, null]);
                });
                maxInput.on('change', function () {
                    slider.noUiSlider.set([null, this.value]);
                });
            }



        });
        $(document).ready(function () {
            // 預先初始化日期選擇器
            $('#daterange2').daterangepicker({
                "showDropdowns": true,
                // "autoApply": true,
                // opens: "center",
                startDate: moment().add(1, 'days'),
                endDate: moment().add(3, 'days'),
                minDate:  moment().add(1, 'days'),
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: "確認",
                    cancelLabel: "取消",
                    fromLabel: "從",
                    toLabel: "到",
                    customRangeLabel: "自訂範圍",
                }
            }, function (start, end) {
                // 檢查是否與不可預約日衝突
                console.log("更新日期");
                let picker = $('#daterange2').data('daterangepicker');
                conflict=false;
                let current = start.clone();
                while (current <= end) {
                    if (unavailableDates.includes(current.format('YYYY-MM-DD'))) {
                        conflict = true;
                        break;
                    }
                    current.add(1, 'days');
                }
                //變紅框顯示ERROR
                if (conflict) {
                    $('#car-info').html("<h5 style='color:red'>日期包含不可預約的日期！</h5>");
                    $('#daterange2').css('color','red');
                    $('#daterange2').css('border-color','red');
                }else{
                    $('#car-info').html("");
                    $('#daterange2').css('color','black');
                    $('#daterange2').css('border-color','black');
                }

            });
            $('.daterangepicker').eq(1).attr('id', 'model_date');
            $('.daterangepicker').eq(1).addClass('model_date');

            // 更新 daterangepicker資料
            function update_picker_data(data){
                let date_picker = $('#daterange2').data('daterangepicker');
                unavailableDates = data;  // 假設這是包含不可用日期的數據

                date_picker.setStartDate($('#daterange').data('daterangepicker').startDate);
                date_picker.setEndDate($('#daterange').data('daterangepicker').endDate);

                date_picker.isInvalidDate = function (date) {
                    let formattedDate = date.format('YYYY-MM-DD');
                    return unavailableDates.includes(formattedDate);  // 若日期在不可用日期清單中，返回 true
                };
                date_picker.updateCalendars();
            }

            // 監聽按鈕點擊事件
            $(".btn-check-availability").click(function () {
                carId = $(this).data('car-id');  // 假設你有設置 car-id
                carBrand = $(this).data('car-brand');
                carModel = $(this).data('car-model');
                cardetail = $(this).data('car-detail');
                carlatefee = $(this).data('car-latefee');
                carfee = $(this).data('car-fee');
                carimage = $(this).data('car-image');
                // 更新 Modal 標題和內容
                $('#availabilityModalLabel').text('預約 - ' + carBrand + ' ' + carModel);

                $.ajax({
                    url: '/rental/search/car/' + carId,
                    type: 'GET',
                    success: function (data) {
                        if (data == "ERROR") {
                            console.log("載入失敗，請稍後再試");
                            $('#car-info').html("<h5 style='color:red'>載入失敗，請稍後再試</h5>");
                            $('.trip-form').hide();
                            $('#availabilityModal').modal('show');
                            return;
                        }
                        /* ----- 以下載入成功 ----- */
                        // 重置保險選擇
                        $('input[name="insurance_id"]').prop('checked', false); // 取消 radio 選擇
                        $('.insurance-card').removeClass('selected');           // 取消選擇樣式
                        selectedInsurancePlan = { name: '', coverage: '' };     // 清空 JS 變數
                        // 設回到第一步
                        currentStep = 1;
                        $('.step').addClass('d-none');
                        $('.step-1').removeClass('d-none');
                        $('.prev-step').addClass('d-none');
                        $('.next-step').removeClass('d-none');
                        $('.submit-form').addClass('d-none');
                        update_picker_data(data);
                        $('#car-info').html("");
                        $('.trip-form').show();
                        $('#availabilityModal').modal('show');
                        checkDateConflict()
                        /**************************/
                    },
                    error: function () {
                        console.log("載入失敗，請稍後再試");
                        $('#car-info').html("<h5 style='color:red'>載入失敗，請稍後再試</h5>");
                        $('.trip-form').hide();
                        $('#availabilityModal').modal('show');
                    }
                });
            });

            //取車還車時間同步
            $('#pickupTime, #pickupTime2').on('input', function () {
                let val = $(this).val();
                $('#pickupTime, #pickupTime2').val(val);
                checkPickupReturnTime();  // 呼叫檢查時間的函數
            });
            $('#returnTime, #returnTime2').on('input', function () {
                let val = $(this).val();
                $('#returnTime, #returnTime2').val(val);
                checkPickupReturnTime();  // 呼叫檢查時間的函數
            });
            function checkPickupReturnTime() {

                var pickupDate = document.getElementById('daterange2').value.split(' - ')[0];
                var pickupTime = document.getElementById('pickupTime').value;
                var returnDate = document.getElementById('daterange2').value.split(' - ')[1];
                var returnTime = document.getElementById('returnTime').value;
                var pickupDateTime = new Date(pickupDate + ' ' + pickupTime);
                var returnDateTime = new Date(returnDate + ' ' + returnTime);

                // 判斷取車時間是否晚於還車時間
                if (pickupDateTime >= returnDateTime) {
                    isTimeValid = false;  // 設定為無效
                    showToast("取車時間必須早於還車時間", "error");
                } else {
                    isTimeValid = true;  // 設定為有效
                }
            }
        });


        /* modal 切頁功能 */
        $(document).ready(function (){
            currentStep=1;
            $('.next-step').click(function () {
                if(conflict){
                    showToast("日期包含不可預約的日期","error");
                    return;
                }
                if(currentStep === 1&&!isTimeValid) {
                    showToast("取車時間必須早於還車時間！","error");
                    return;
                }
                if(currentStep === 3) {
                    const selectedInsurance = $('input[name="insurance_id"]:checked').val();
                    if(!selectedInsurance){
                        showToast("請選擇一個保險方案！","error");
                        return;
                    }
                    goToStep4();
                }


                $('.step').addClass('d-none');
                currentStep++;
                $(`.step-${currentStep}`).removeClass('d-none');

                $('.prev-step').toggleClass('d-none', currentStep === 1);
                $('.next-step').toggleClass('d-none', currentStep === 4);
                $('.submit-form').toggleClass('d-none', currentStep !== 4);
                if(currentStep === 2){
                    const isLoggedIn = {{ session()->has('user_data') && session('user_data')['active'] ? 'true' : 'false' }};
                    if(!isLoggedIn){
                        // 替換 .next-step 按鈕為前往登入的連結
                        $('.next-step').replaceWith(`
                        <a href="{{ route('google.auth.page') }}" class="btn btn-warning next-step">
                            <i class="fa fa-sign-in"></i> 前往登入
                        </a>
                        `);
                    }
                }
            });

            $('.prev-step').click(function () {
                $('.step').addClass('d-none');
                currentStep--;
                $(`.step-${currentStep}`).removeClass('d-none');

                $('.prev-step').toggleClass('d-none', currentStep === 1);
                $('.next-step').toggleClass('d-none', currentStep === 4);
                $('.submit-form').toggleClass('d-none', currentStep !== 4);
            });

            $(document).on('change', 'input[name="insurance_id"]', function () {
                // 找到被選中的 input
                let selectedInput = $(this);

                // 透過 input 去找到對應的 label 和說明文字
                let card = selectedInput.closest('.insurance-card'); // 找到最接近的卡片
                let insuranceName = card.find('label').text().trim(); // 抓保險名稱
                let insuranceCoverage = card.find('p').text().trim(); // 抓細項內容
                // 更新記錄變數
                selectedInsurancePlan.name = insuranceName;
                selectedInsurancePlan.coverage = insuranceCoverage;
            });
            $('.insurance-card').on('click', function () {
                const id = $(this).data('id');
                insurance_fee = parseInt($(this).data('fee'));

                $('#insurance_' + id).prop('checked', true).trigger('change');
                $('.insurance-card').removeClass('selected');
                $(this).addClass('selected');
            });
            //最終送出預約資訊
            $('.submit-form').on('click', function(e) {
                e.preventDefault();
                // 組資料
                let data = {
                    pickupDate: $('#daterange2').val().split(' - ')[0],
                    returnDate: $('#daterange2').val().split(' - ')[1],
                    pickup_time: $('#pickupTime2').val(),
                    return_time: $('#returnTime2').val(),
                    car_id: carId,
                    insurance_id: $('input[name="insurance_id"]:checked').val(),
                    _token: '{{ csrf_token() }}',
                    location_id:$('#location').val()
                };
                $.ajax({
                    url: '{{ route("rental_reserve") }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if(response['status']=="success"){
                            $('#availabilityModal').modal('hide'); // 關閉 Modal
                            document.getElementById('search_form').submit();
                        }else{
                            showToast(response['message'], 'error')
                        }
                    },
                    error: function() {
                        // 錯誤處理
                        showToast('預約失敗，請稍後再試', 'error')
                        console.log(response['message']);
                    }
                });
            });
        })

        function goToStep4() {
            var showCarModel = carBrand + " " + carModel + " " + cardetail;
            @if(session()->has('user_data') && session('user_data')['active'])
            var memberEmail = "{{session('user_data')['email']}}";
            var memberPhone = "{{session('user_data')['phone']}}";
            @endif
            var pickupDate = document.getElementById('daterange2').value.split(' - ')[0];
            var pickupTime = document.getElementById('pickupTime').value;
            var returnDate = document.getElementById('daterange2').value.split(' - ')[1];
            var returnTime = document.getElementById('returnTime').value;
            $('#main-car-image').attr('src', carimage);
            document.getElementById('summary_car_model').innerText = showCarModel;
            document.getElementById('summary_email').innerText = memberEmail;
            document.getElementById('summary_phone').innerText = memberPhone;
            document.getElementById('summary_pickup').innerText = pickupDate + " " + pickupTime;
            document.getElementById('summary_return').innerText = returnDate + " " + returnTime;
            document.getElementById('summary_insurance').innerHTML = selectedInsurancePlan['name'] + "<br>" + selectedInsurancePlan['coverage'];
            document.getElementById('summary_latefee').innerHTML  = carlatefee;
            // 計算費用
            var pickupDateTime = new Date(pickupDate + ' ' + pickupTime);
            var returnDateTime = new Date(returnDate + ' ' + returnTime);
            var diffMs = returnDateTime - pickupDateTime;
            var diffHours = diffMs / (1000 * 60 * 60);
            var days = Math.floor(diffHours / 24); //計算滿的一天
            var total_cost,formulaText
            var remainHours = diffHours % 24;
            if (remainHours >= 1) {
                days += 1;
                total_cost=days*(carfee+insurance_fee); //算式
                formulaText = `${days}天 × 每日租金(車輛費 ${carfee} + 保險費 ${insurance_fee}) = <span style="color:red;">NT$ ${total_cost}元</span>`;
            }
            else{
                days = Math.max(days, 1);
                total_cost=(days)*(carfee+insurance_fee); //算式
                formulaText = `NT$ ${days}天 × 每日租金(車輛費 ${carfee} + 保險費 ${insurance_fee}) = <span style="color:red;">${total_cost}元</span>`;
            }
            document.getElementById('summary_cost').innerHTML  = formulaText;
        }

        {{------------------------------------------- 新增日期不限功能 -------------------------------------------}}
        $(document).ready(function () {
            // 初次載入時判斷並設置日期範圍選擇器顯示狀態
            if ($('#noDateLimit').is(':checked')) {
                $('#daterange').css('display', 'none');
                $('#SelectTimeForm').css('display',"none");
            } else {
                $('#daterange').css('display', 'block');
                $('#SelectTimeForm').css('display',"block");
            }

            // 當勾選「不限日期」時，隱藏日期範圍選擇器
            $('#noDateLimit').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#daterange').css('display', "none");
                    $('#SelectTimeForm').css('display',"none");
                } else {
                    $('#daterange').css('display', 'block');
                    $('#SelectTimeForm').css('display',"block");
                }
            });
        });
        function checkDateConflict() {
            let date_picker = $('#daterange2').data('daterangepicker');
            conflict = false;
            start=$('#daterange').data('daterangepicker').startDate
            end=$('#daterange').data('daterangepicker').endDate
            current=start.clone()
            while (current <= end) {
                if (unavailableDates.includes(current.format('YYYY-MM-DD'))) {
                    conflict = true;
                    break;
                }
                current.add(1, 'days');
            }
            // 顯示結果
            if (conflict) {
                $('#car-info').html("<h5 style='color:red'>日期包含不可預約的日期！</h5>");
                $('#daterange2').css('color', 'red');
                $('#daterange2').css('border-color', 'red');
            } else {
                $('#car-info').html("");
                $('#daterange2').css('color', 'black');
                $('#daterange2').css('border-color', 'black');
            }
        }
        {{-----------------------------------------------------------------------------------------------------}}
        //顯示更多此車款按鈕
        $(function(){
            $('.btn-show-more').on('click', function(){
                var modelId = $(this).data('model-id');
                $('.model-' + modelId).show();
                $('.btn-show-more[data-model-id="' + modelId + '"]').html("");
            });
        });
    </script>


@endsection
