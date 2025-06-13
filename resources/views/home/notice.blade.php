@section('title') 123．租車 | 注意事項 @endsection
@extends('home/blade/master')

@section('source_css')
    {{--    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">--}}
    {{--    <link rel="stylesheet" href="{{asset('dist/css/daterangepicker.min.css')}}"/>--}}
    {{--    <link rel="stylesheet" href="{{asset('dist/css/nouislider.css')}}"/>--}}
@endsection
@section('source_js')
    {{--    <script src="{{asset('dist/js/moment.min.js')}}"></script>--}}
    {{--    <script src="{{asset('dist/js/daterangepicker.min.js')}}" defer></script>--}}
    {{--    <script src="{{asset('dist/js/nouislider.min.js')}}" defer></script>--}}
    {{--    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>--}}
@endsection

@section('main_section')
<div class="container mb-5" style="margin-top:150px;">
    <h1 class="mt-6 text-center">注意事項</h1>

    <!-- 保險方案 -->
    <h2 class="mb-4">保險方案</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3"><strong>基本保險方案</strong></h5>
            <p><strong>保障內容：</strong></p>
            <ul>
                <li>第三人責任保險：每人傷害上限 200 萬、每事故傷害上限 400 萬、財損上限 50 萬</li>
                <li>駕駛人保險：100 萬</li>
                <li>乘客保險：100 萬</li>
            </ul>
            <p><strong>保險費用：</strong>免費</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3"><strong>第三人責任升級版</strong></h5>
            <p><strong>保障內容：</strong></p>
            <ul>
                <li>每人傷害上限提升至 500 萬</li>
                <li>每事故傷害上限提升至 1000 萬</li>
                <li>財損上限提升至 200 萬</li>
                <li>其餘條件比照基本方案</li>
            </ul>
            <p><strong>保險費用：</strong>200 元</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3"><strong>第三人責任與車體損害升級版</strong></h5>
            <p><strong>保障內容：</strong></p>
            <ul>
                <li>每人傷害上限提升至 500 萬</li>
                <li>每事故傷害上限提升至 1000 萬</li>
                <li>財損上限提升至 200 萬</li>
                <li>車輛全損自負額降至：國產車 10 萬、進口車 20 萬</li>
            </ul>
            <p><strong>保險費用：</strong>400 元</p>
        </div>
    </div>

    <!-- 🔽 新增補充保險說明 -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3"><strong>保險補充說明</strong></h5>
            <ul>
                <li><strong>強制險：</strong>依法為所有車輛投保，保障第三方人員傷亡。</li>
                <li><strong>失竊險：</strong>如遇車輛被竊，將依據保險條款給予理賠。</li>
                <li><strong>不理賠情況：</strong>酒駕、無照駕駛、未報警處理、非法用途等皆不在保障範圍內。若有任何損害，需自行賠償。</li>
            </ul>
        </div>
    </div>

    <!-- 服務條款 -->
    <h2 class="mt-5 mb-4">服務條款</h2>
    <div class="card">
        <div class="card-body">
            <h5>1️⃣ 使用者資格</h5>
            <ul>
                <li>您必須年滿 18 歲以上並持有有效駕照，方可註冊與使用本租車系統。</li>
                <li>註冊時所填寫的資料須為真實、正確，若有虛假資料，平台有權中止帳號使用權。</li>
            </ul>

            <h5>2️⃣ 租車預約與取消</h5>
            <ul>
                <li>預約成功後，若需取消或更改，請依平台規定時間內完成，逾期可能會收取費用。</li>
                <li>若因個人因素未如期取車，平台保有拒絕退款及提供服務的權利。</li>
            </ul>

            <h5>3️⃣ 車輛使用規範</h5>
            <ul>
                <li>租用車輛期間，請遵守當地交通法規，若有違法，使用者須自行負責法律責任。</li>
                <li>租賃期間如發生損壞、事故或交通罰單，須由租車人全額負擔處理與賠償。</li>
                <li>禁止將車輛用於非法活動，包括但不限於運輸違禁品、非法競速等。</li>
            </ul>

            <h5>4️⃣ 費用與付款</h5>
            <ul>
                <li>所有租金、保險與額外服務費用，將於預約時列明並由租車人支付。</li>
                <li>若因個人原因取消訂單，可能會收取一定比例的手續費。</li>
            </ul>

            <h5>5️⃣ 資料隱私保護</h5>
            <ul>
                <li>本平台依法保護您的個人資料，不會任意提供給第三方，除非法律另有規定。</li>
                <li>您的聯絡方式僅用於訂單通知與必要服務聯繫。</li>
            </ul>

            <h5>6️⃣ 系統權利與責任</h5>
            <ul>
                <li>本平台有權隨時修改服務條款，請使用者定期查閱最新內容。</li>
                <li>因安全、維護或法規需求，平台有權暫停或終止部分服務，並盡可能提前通知用戶。</li>
            </ul>

            <h5>7️⃣ 違約與終止</h5>
            <ul>
                <li>若用戶違反本條款，平台有權終止服務並保留法律追訴權。</li>
                <li>包括但不限於：虛假註冊、盜用帳號、惡意破壞車輛等行為。</li>
            </ul>
        </div>
    </div>
    <!-- 常見問題 -->
    <h2 class="mt-5 mb-4">常見問題</h2>
    <div class="card">
        <div class="card-body">
            <h5>❓ 如何租車？</h5>
            <ul class="ml-4">
                <li>您可於平台選擇欲租車輛與日期，完成線上預約後，即可於指定時間取車。</li>
            </ul>

            <h5>❓ 取車需要攜帶哪些證件？</h5>
            <ul class="ml-4">
                <li>請攜帶您的身分證及有效駕照，並出示訂單畫面供工作人員核對。</li>
            </ul>

            <h5>❓ 是否可以更改預約內容？</h5>
            <ul class="ml-4">
                <li>預約完成後，請於平台提供的更改期限內申請變更，逾時請聯絡客服人員。</li>
            </ul>

            <h5>❓ 若車輛出現問題，該怎麼辦？</h5>
            <ul class="ml-4">
                <li>請立即聯繫客服人員，我們會協助您處理換車或退款相關事宜。</li>
            </ul>

            <h5>❓ 聯絡資訊</h5>
            <ul class="ml-4">
                <li>聯繫客服人員:<b>05-6315000</b> 我們將24小時提供服務</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('main_css')
<style>
    h2 {
        font-weight: bold;
        color: #333;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .card-body ul {
        padding-left: 20px;
    }
    .card-body ul li {
        margin-bottom: 10px;
    }
    h5 {
        font-weight: bold;
        margin-top: 20px;
    }
</style>
@endsection

