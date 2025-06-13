<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Location;
use App\Models\Member;
use App\Models\Rental_Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Rental;
use Carbon\Carbon;

class MemberController{
    public function member_center(){
        // 檢查使用者是否已登入且活躍
        if (!(session()->has('user_data') && session('user_data')['active'])) {
            return redirect()->route('google.auth.page');
        }

        // 根據會員的電子郵件查找會員ID
        $member_id = Member::search_member_id(session('user_data.email'));
        $orders = Rental::search_member_order($member_id);
        $orders = collect($orders);
        $insurances=Insurance::search_all();
        if(!empty($orders)){
            foreach($orders as $order){
                $pickupDateTime=Carbon::parse($order->start_date); $returnDateTime=Carbon::parse($order->end_date);
                $diffInHours = $pickupDateTime->diffInHours($returnDateTime);
                $days = intdiv($diffInHours, 24);
                $remainHours = $diffInHours % 24;
                if ($remainHours < 1) {
                    $total_days = $days;
                } else {
                    $total_days = $days + 1;
                }
                $total_days=max($total_days,1);
                $carfee = Rental::search_car_fee($order->car_id);
                $insurancefee = Rental::search_insurance_fee($order->insurance_id);
                $show_account_calc = "{$total_days}天 × (車輛費{$carfee} + 保險費{$insurancefee}) = ".$total_days * ($carfee + $insurancefee)." 元";
                $order->show_account_calc=$show_account_calc;
            }
        }

        //計算顯示下一次簡訊驗證等待時間
        $lastSendTime = session('last_send_code');
        $remaining=0;
        if (!empty($lastSendTime)) {
            $secondsSinceLastSend = abs(now()->diffInSeconds($lastSendTime));
            if ($secondsSinceLastSend < 60) {
                $remaining = intval(60 - $secondsSinceLastSend);
            }
        }
        // 根據租借狀態分組訂單
        $ordersGrouped = $orders->groupBy('rental_status');

        $data=[
            'orders' => $orders,
            'ordersGrouped' => $ordersGrouped,
            'insurances'=>$insurances,
            'remaining'=>$remaining
        ];
        // 傳遞訂單資料和分組後的資料到視圖
        return view('home.member',$data);
    }
    public function rental_trash(Request $request){
        //Stage1:檢查session有效
        if (!(session()->has('user_data') && session('user_data')['active'])) {
            return response()->json([
                'status' => 'error',
                'message' => '會員有效期限過期',
            ]);
        }
        //Stage2:檢查傳入欄位
        $validated = $request->validate([
            'RentalId' => 'required'
        ]);
        $RentalId = $validated['RentalId'];
        $user_id=Member::search_member_id(session('user_data.email')); //查詢ID
        //Stage3: 檢查是否為該會員資料
        $chk_memID=Rental::search_order_owner($RentalId);
        if(empty($chk_memID)||$user_id!=$chk_memID){
            return response()->json([
                'status' => 'error',
                'message' => '404_NOT_FOUND',
            ]);
        }
        //Stage4: 檢查是時間是否合法
        $order = Rental::search_order_data($RentalId);
        $now = Carbon::now();
        $createdAt = Carbon::parse($order->created_at);
        $startDate = Carbon::parse($order->start_date)->startOfDay(); // 取車日 00:00
        $normalLine = $startDate->copy()->subDays(2); // 正常截止日

        // 判斷是否為臨時訂單（建立時間在 normalLine 之後，且現在 < 取車日）
        $isTemp = ($createdAt->gt($normalLine)) && ($now->lt($startDate));

        // 計算 deadline
        if ($now->lt($normalLine)) {
            $deadLine = $normalLine->copy()->subSecond(); // 正常訂單 deadline
        } elseif ($isTemp) {
            $deadLine = min(
                $createdAt->copy()->addMinutes(15),
                $startDate
            )->copy()->subSecond(); // 臨時單：15分鐘內或取車日前，取較早者
        } else {
            $deadLine = null;
        }

        // 判斷是否可取消（付款狀態、訂單狀態、時間是否未超過 deadline）
        $canCancel = ($order->rental_status == 'pending' || $order->rental_status == 'active')
            && ($order->payment_status == 0)
            && ($deadLine instanceof Carbon)
            && ($now->lt($deadLine));
        if (!$canCancel) {
            return response()->json([
                'status' => 'error',
                'message' => '訂單已逾期無法取消',
            ]);
        }
        //Stage5: 更新「取消訂單」動作
        if (Rental::update_order_status($RentalId, 'cancelled') > 0) {
            session(['web_status'=>'success']);
            session(['web_status_description' => '訂單取消成功']);
            return response()->json([
                'status' => 'success',
                'message' => '訂單取消成功',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => '訂單無法取消',
        ]);
    }
    public function rental_edit(Request $request){
        //Stage1:檢查session有效
        if (!(session()->has('user_data') && session('user_data')['active'])) {
            return response()->json([
                'status' => 'error',
                'message' => '會員有效期限過期',
            ]);
        }
        //Stage2:檢查傳入欄位
        $validated = $request->validate([
            'rentalId' => 'required',
            'insurance_id' => 'required'
        ]);
        $RentalId = $validated['rentalId'];
        $insurance_id = $validated['insurance_id'];
        $user_id=Member::search_member_id(session('user_data.email')); //查詢ID
        //Stage3: 檢查是否為該會員資料
        $chk_memID=Rental::search_order_owner($RentalId);
        if(empty($chk_memID)||$user_id!=$chk_memID){
            return response()->json([
                'status' => 'error',
                'message' => '404_NOT_FOUND',
            ]);
        }
        //Stage4:確認保險方案可用
        if (!(Insurance::check_insurance_available($insurance_id))) {
            return response()->json([
                'status' => 'error',
                'message' => 'insurance ERROR',
            ]);
        }
        //Stage5: 檢查時間是否合法
        $order = Rental::search_order_data($RentalId);
        $now = Carbon::now();
        $createdAt = Carbon::parse($order->created_at);
        $startDate = Carbon::parse($order->start_date)->startOfDay(); // 取車日 00:00
        $normalLine = $startDate->copy()->subDays(2); // 正常截止日

        // 判斷是否為臨時訂單（建立時間在 normalLine 之後，且現在 < 取車日）
        $isTemp = ($createdAt->gt($normalLine)) && ($now->lt($startDate));

        // 計算 deadline
        if ($now->lt($normalLine)) {
            $deadLine = $normalLine->copy()->subSecond(); // 正常訂單 deadline
        } elseif ($isTemp) {
            $deadLine = min(
                $createdAt->copy()->addMinutes(15),
                $startDate
            )->copy()->subSecond(); // 臨時單：15分鐘內或取車日前，取較早者
        } else {
            $deadLine = null;
        }

        // 判斷是否可取消（付款狀態、訂單狀態、時間是否未超過 deadline）
        $canEdit = ($order->rental_status == 'pending' || $order->rental_status == 'active')
            && ($order->payment_status == 0)
            && ($deadLine instanceof Carbon)
            && ($now->lt($deadLine));
        if (!$canEdit) {
            return response()->json([
                'status' => 'error',
                'message' => '訂單已逾期無法修改',
            ]);
        }

        //Stage6: 預先計算費用
        /******************************** 計算總額 *******************************/
        $rental_data=Rental::search_order_data($RentalId);
        if($insurance_id==$rental_data->insurance_id){
            return response()->json([
                'status' => 'error',
                'message' => '訂單無修改',
            ]);
        }
        $pickupDateTime=Carbon::parse($rental_data->start_date); $returnDateTime=Carbon::parse($rental_data->end_date);
        $diffInHours = $pickupDateTime->diffInHours($returnDateTime);
        $days = intdiv($diffInHours, 24);
        $remainHours = $diffInHours % 24;
        if ($remainHours < 1) {
            $total_days = $days;
        } else {
            $total_days = $days + 1;
        }
        $total_days=max($total_days,1);
        $carfee = Rental::search_car_fee($rental_data->car_id);
        $insurancefee = Rental::search_insurance_fee($insurance_id);
        $total_amount = $total_days * ($carfee + $insurancefee);
        //Stage7: 更新訂單動作
        if (Rental::update_order_insurance($RentalId, $insurance_id,$total_amount) > 0) {
            session(['web_status'=>'success']);
            session(['web_status_description' => '訂單修改成功']);
            return response()->json([
                'status' => 'success',
                'message' => '訂單修改成功',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => '訂單無法修改',
        ]);
    }
    public function update_phone(Request $request){
        $requests=$request->all();
        session(['user_data.verify_code_retry'=> session('user_data.verify_code_retry')+1]);
        if($requests['phone']==session('user_data.verify_phone')&&
            $requests['code']==session('user_data.verify_code')&&
            now()->lessThan(session('user_data.verify_expires'))&&
            session('user_data.verify_code_retry') <= 3
        ){
            Member::update_phone(session('user_data.email'),$requests['phone']);
            session(['user_data.phone'=>session('user_data.verify_phone')]);
            session(['user_data.verify_phone'=>""]);
            session(['user_data.active'=>true]);
            session(['web_status'=>'success']);
            session(['web_status_description' => '修改成功']);
            return response()->json([
                'status' => 'success',
                'message' => '修改成功'
            ]);
        };
        if(session('user_data.verify_code_retry')>3){
            session(['user_data.verify_phone' => ""]);
            session(['user_data.verify_code'=>""]);
            return response()->json([
                'status' => 'error',
                'message' => '驗證碼輸入錯誤多次，請重新取得驗證碼。'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => '驗證碼錯誤，請重新輸入。'
        ]);
    }
}

