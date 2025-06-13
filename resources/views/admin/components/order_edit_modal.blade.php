<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">#<sapn id="modal-id"></sapn>&ensp;-&ensp;訂單詳細資訊</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <h4 class="text-center">會員資訊</h4>
                    <div class="mb-2"><b>申請人：</b><span id="modal-gmail"></span></div>
                    <div class="mb-2"><b>電話：</b><span id="modal-phone"></span></div>
                    <hr>
                    <h4 class="text-center">車籍資訊</h4>
                    <div class="listing-img d-flex justify-content-center align-items-center">
                        <img src="" alt="Image" class="img-fluid" id="modal-image_url">
                    </div>
                    <div class="mb-2"><b>車款：</b><span id="modal-model"></span></div>
                    <div class="mb-2"><b>車類型：</b><span id="modal-car_type"></span></div>
                    <div class="mb-2"><b>燃油方式：</b><span id="modal-fuel_type"></span></div>
                    <div class="mb-2"><b>CC數：</b><span id="modal-engine_cc"></span></div>
                    <div class="mb-2"><b>傳動方式：</b><span id="modal-transmission"></span></div>
                    <div class="mb-2"><b>可乘坐人數：</b><span id="modal-seat_num"></span></div>
                    <div class="mb-2"><b>車牌：</b><span id="modal-plate"></span></div>
                    <div class="mb-2"><b>延時費用(NT$/每小時)：</b><span id="modal-late_fee"></span> 元</div>
                    <hr>
                    <h4 class="text-center">訂單資訊</h4>
                    <div class="mb-2"><b>取還車地點：</b><span id="modal-loc"></span></div>
                    <div class="mb-2"><b>租用結束時間：</b><span id="modal-start"></span></div>
                    <div class="mb-2"><b>租用結束時間：</b><span id="modal-end"></span></div>
                    <div class="mb-2">
                        <label for="modal-ins_select"><h6><b>狀態：</b></h6></label>
                        <select id="modal-status" name="status" class="form-select">
                            <option value="pending">未審核</option>
                            <option value="active">審核通過</option>
                            <option value="completed">已完成</option>
                            <option value="cancelled">顧客取消</option>
                            <option value="reject">拒絕</option>
                        </select>
                    <div class="mb-2">
                        <label for="modal-ins_select"><h6><b>保險方案：</b></h6></label>
                        <select id="modal-ins_select" name="insurance_id" class="form-select">
                            @foreach ($insurances as $insurance)
                                <option value="{{ $insurance->insurance_id }}">
                                    {{ $insurance->ins_name }}（費用 + NT${{ $insurance->ins_fee }}）
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2"><b>保險項目：</b><br><span id="modal-coverage"></span></div>
{{--                    <div class="mb-2"><b>保險每日費用：</b><span id="modal-ins_fee"></span></div>--}}
{{--                    <div class="mb-2"><b>每日租金(NT$/天)：</b><span id="modal-daily_fee"></span> 元</div>--}}
                    <hr>
                    <h4 class="text-center">付款資訊</h4>
{{--                    <div class="mb-2"><b>總費用：</b><span id="modal-cost"></span></div>--}}
                    <div class="mb-2"><b>總費用(未更動前)：</b><span id="modal-show_account_calc"></span></div>
                    <div class="mb-2"><b>總費用(更動後)：</b><span id="modal-show_account_calc_new"></span></div>
                    {{--                    <div class="mb-2"><b>付款方式：</b><span id="modal-method"></span></div>--}}
{{--                    <div class="mb-2"><b>付款狀態：</b><span id="modal-payment_status"></span></div>--}}
                    <div class="mb-2">
                        <label for="modal-method_select"><h6><b>付款方式：</b></h6></label>
                        <select id="modal-method_select" name="payment_method" class="form-select">
                            <option value="cash">現金</option>
                            <option value="credit">信用卡</option>
                            <option value="linepay">Line Pay</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="modal-payment_status_select"><h6><b>付款狀態：</b></h6></label>
                        <select id="modal-payment_status_select" name="payment_status" class="form-select">
                            <option value="0">未付款</option>
                            <option value="1">已付款</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">儲存變更</button>

            </div>
        </div>
    </div>
</div>



<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">確定儲存變更</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="change-summary"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="confirm-save">儲存變更</button>
            </div>
        </div>
    </div>
</div>
