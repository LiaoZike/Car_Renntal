@section('title') 123．租車 @endsection
@extends('home/blade/master')

@section('source_css')
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">
<link rel="stylesheet" href="{{asset('dist/css/daterangepicker.min.css')}}"/>
{{--<link rel="stylesheet" href="{{asset('dist/css/all.css')}}">--}}
@endsection
@section('source_js')
    <script src="{{asset('dist/js/moment.min.js')}}"></script>
    <script src="{{asset('dist/js/daterangepicker.min.js')}}" defer></script>
@endsection

@section('main_section')
    <div class="hero" style="background-image: url({{asset('images/hero_1_b.jpg')}})">

{{--    <div class="hero" style="background-image: url('images/hero_1_b.jpg');">--}}
     <div class="container">
         <div class="row align-items-center justify-content-center">
             <div class="col-lg-10">

                 <div class="row mb-5">
                     <div class="col-lg-12 intro">
                         <h1 style="color:white;"><strong>租車Easy </strong>現在就出遊!</h1>
                     </div>
                 </div>


                 <form class="trip-form" action="{{route('rental_search_post')}}" method="POST">
                     @csrf()
                     <div class="container">
                         <div class="form-group">
                             <label for="location"><b>取還車站點</b></label>
                             <select class="form-control" id="location" name="location">
                                 @if(!empty($locations))
                                    @foreach($locations as $location)
                                    <option value="{{$location->loc_id}}">{{$location->loc_name}}
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label><b>選擇日期區間：</b></label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="noDateLimit" name="noDateLimit">
                                    <label class="form-check-label" for="noDateLimit">不限日期</label>
                                </div>
                                <input type="text" name="daterange" id="daterange" class="form-control"/>
                            </div>
                            <div class="form-group" id="SelectTimeForm">
                                <label for="pickupTime">取車時間</label>
                                <input type="time" id="pickupTime" name="pickupTime" value="09:00">&ensp;
                                <label for="returnTime">還車時間</label>
                                <input type="time" id="returnTime" name="returnTime" value="18:00">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label>目前選擇：</label><pre id="result"></pre>--}}
{{--                            </div>--}}

                            <div class="form-group type_select_div">
                                <label><b>選擇車種：</b></label><br>
                                <div class="inline-checkbox">
                                 <label><input type="checkbox" name="car_type[]" value="Any" id="car_type_any" checked> 不限</label>
                                 <label><input type="checkbox" name="car_type[]" value="Compact"> 小型車</label>
                                 <label><input type="checkbox" name="car_type[]" value="Sedan"> 中大型房車</label>
                                 <label><input type="checkbox" name="car_type[]" value="SUV"> SUV</label>
                                 <label><input type="checkbox" name="car_type[]" value="MPV"> MPV</label>
                             </div>
                         </div>
                     </div>
                      <div class="mb-3 mb-md-0 col-md-3">
                          <button type="submit" class="btn btn-primary btn-block py-3">
                              <i class="fa fa-car" aria-hidden="true"></i>&ensp;我想租車
                          </button>
                      </div>

                 </form>

             </div>
         </div>
     </div>
</div>


     <div class="site-section">
         <div class="container">
             <h2 class="section-heading"><strong>如何租車？</strong></h2>
             <p class="mb-5">簡單三步驟</p>

             <div class="row mb-5">
                 <div class="col-lg-4 mb-4 mb-lg-0">
                     <div class="step">
                         <span>1</span>
                         <div class="step-inner">
                             <span class="number text-primary">01.</span>
                             <h3>選擇車輛</h3>
                             <p>挑選一台符合您需求的車輛</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-4 mb-4 mb-lg-0">
                     <div class="step">
                         <span>2</span>
                         <div class="step-inner">
                             <span class="number text-primary">02.</span>
                             <h3>填寫租車表單</h3>
                             <p>填寫基本資料，確認租車時間與地點。</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-4 mb-4 mb-lg-0">
                     <div class="step">
                         <span>3</span>
                         <div class="step-inner">
                             <span class="number text-primary">03.</span>
                             <h3>租車日取車</h3>
                             <p>依預約日期，到現場簽約並領取車輛！</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    @php
        $features = [
            [
                'icon' => 'icon-watch_later',
                'title' => '彈性取還車',
                'desc' => '彈性安排取車與還車時間，讓行程更自由自在。'
            ],
            [
                'icon' => 'icon-verified_user',
                'title' => '全面保障',
                'desc' => '車輛投保完整，出行更有保障，無憂享受每段旅程。'
            ],
            [
                'icon' => 'icon-vpn_key',
                'title' => '快速取車',
                'desc' => '線上預約，現場即取，節省您的寶貴時間。'
            ],
        ];
    @endphp
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="section-heading"><strong>Features</strong></h2>
                    <p class="mb-5">我們提供優質的租車服務，讓您旅途愉快。</p>
                </div>
            </div>

            <div class="row">
                @foreach($features as $feature)
                    <div class="col-lg-4 mb-5">
                        <div class="service-1 dark">
                        <span class="service-1-icon">
                            <span class="{{ $feature['icon'] }}"></span>
                        </span>
                            <div class="service-1-contents">
                                <h3>{{ $feature['title'] }}</h3>
                                <p>{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="site-section bg-primary py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-md-0">
                    <h2 class="mb-0 text-white">你還在等什麼?</h2>
                    <h2 class="mb-0 text-white">聯絡我們 <i class="fa fa-phone" aria-hidden="true"></i> 05-6315000</h2>
                    <p class="mt-2 mb-0 text-white">地址：632 雲林縣虎尾鎮文化路 64 號</p>
                </div>
                <div class="col-lg-5 text-md-right">
                    <a href="{{route('rental_search')}}" class="btn btn-primary btn-white">現在就來租車!</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main_css')
<style>
    .type_select_div label,.type_select_div input{
        cursor: pointer;
    }
    .type_select_div{
        user-select: none;
    }
    .type_select_div label{
        margin-right: 10px;
    }

    .daterangepicker td.off.disabled {
        background-color: rgba(177, 177, 177, 0.56) !important;
    }
    .daterangepicker td.off.disabled:hover{
        color:#999 !important;
        background-color: rgba(177, 177, 177, 0.56) !important;
    }
</style>
@endsection
@section('main_js')
<script>
    $(function() {
        // 初始化日期選擇器
        $('#daterange').daterangepicker({
        startDate: moment().add(1, 'days'),
        endDate: moment().add(3, 'days'),
        minDate:  moment().add(1, 'days'),
        locale: {
        format: 'YYYY-MM-DD',
        applyLabel: "確認",
        cancelLabel: "取消",
        fromLabel: "從",
        toLabel: "到",
        customRangeLabel: "自訂範圍"
    }
    }, function(start, end) {
        // updateResult(start, end); // 選擇日期時更新
    });

        // 當時間輸入改變時也更新
        $('#pickupTime, #returnTime').on('change', function() {
        // updateResult();
    });

        function updateResult(startDate = null, endDate = null) {
            // 如果有選新的日期，就使用參數，否則用 daterangepicker 裡的值
            let dateRange = $('#daterange').data('daterangepicker');

            let pickupTime = $('#pickupTime').val() || '未選取';
            let returnTime = $('#returnTime').val() || '未選取';

            let startText = (startDate || dateRange.startDate).format('YYYY-MM-DD') + " " + pickupTime;
            let endText = (endDate || dateRange.endDate).format('YYYY-MM-DD') + " " + returnTime;

            let resultText = `起始日期: ${startText}\n結束日期: ${endText}`;
            $('#result').text(resultText);
        }
    });

    $(function () {
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
    });
    $(document).ready(function (){
        $('#noDateLimit').on('change', function () {
            if ($(this).is(':checked')) {
                $('#daterange').css('display',"none");
                $('#SelectTimeForm').css('display',"none");
            } else {
                $('#daterange').css('display', 'block');
                $('#SelectTimeForm').css('display',"block");
            }
        });
    });
</script>

@endsection
