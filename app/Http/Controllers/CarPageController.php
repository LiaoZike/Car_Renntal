<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Member;
use App\Models\Rental_Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Rental;
use Carbon\Carbon;

class CarPageController{
    public function getAvailability($carId){
        $today = Carbon::today(); // 今天的日期
        $check_car=Rental_Mix::checkCarID($carId);
        if($check_car=="ERROR") return $check_car;
        // 查詢該車輛的所有租賃紀錄，並過濾掉那些已經結束的租賃紀錄
        $rentalRecords = Rental_Mix::getCarRentalDates($carId);
        // 處理每個租賃紀錄，保留與今天有交集的日期
        $dates = [];
        foreach ($rentalRecords as $record) {
            $start = Carbon::parse($record['start_date']);
            $end = Carbon::parse($record['end_date']);

            // 如果租賃紀錄的結束時間在今天之後
            if ($end >= $today) {
                // 從租賃紀錄的結束日期開始，逐日處理
                while ($start <= $end) {
                    if ($start >= $today) { //今天之後再紀錄
                        $dates[] = $start->toDateString();
                    }
                    $start->addDay(); // 逐天增加
                }
            }
        }
        return response()->json($dates); // ✅ 改這裡！

//        return $dates; // 回傳所有不包含今天之前的日期
    }

    public function reserve(Request $request){
        // 驗證資料
        if(!(session()->has('user_data')&&session('user_data.active')===true)){
            return response()->json([
                'status' => 'error',
                'message' => '會員有效期限過期',
            ]);
        }
        $validated = $request->validate([
            'car_id' => 'required|integer',
            'pickupDate' => 'required|date',
            'pickup_time' => 'required',
            'returnDate' => 'required|date',
            'return_time' => 'required',
            'insurance_id' => 'required|integer',
            'location_id'=>'required|integer'
        ]);

        $startDateTime = $validated['pickupDate'] . ' ' . $validated['pickup_time']; // 例: 2025-05-19 08:00:00
        $endDateTime = $validated['returnDate'] . ' ' . $validated['return_time'];
        $pickupDateTime = Carbon::parse($startDateTime);
        $returnDateTime = Carbon::parse($endDateTime);
        if ($returnDateTime <= $pickupDateTime) {
            return response()->json([
                'status' => 'error',
                'message' => '還車時間必須晚於借車時間！',
            ]);
        }
        //後端檢查預約時間無衝突
        $rentedDates = Rental_Mix::getCarRentalDates($validated['car_id']);
        foreach ($rentedDates as $rented) {
            $rentedStart = Carbon::parse($rented['start_date']);
            $rentedEnd = Carbon::parse($rented['end_date']);

            //檢查租用日期不重複
            if ($validated['pickupDate'] < $rentedEnd && $validated['returnDate'] > $rentedStart) {
                return response()->json([
                    'status' => 'error',
                    'message' => '所選時間已有其他租借，請選擇其他時間！',
                ]);
            }
        }
        // 整理資料
        $memberId=Member::search_member_id(session('user_data.email'));
        $car_id = $validated['car_id'];
        $insurance_id=$validated['insurance_id'];
        $location_id=$validated['location_id'];
        $method='cash';


        /******************************** 計算總額 *******************************/
        $diffInHours = $pickupDateTime->diffInHours($returnDateTime);
        $days = intdiv($diffInHours, 24);
        $remainHours = $diffInHours % 24;
        if ($remainHours < 1) {
            $total_days = $days;
        } else {
            $total_days = $days + 1;
        }
        $total_days=max($total_days,1);
        $carfee = Rental::search_car_fee($car_id);
        $insurancefee = Rental::search_insurance_fee($insurance_id);
        $total_amount = $total_days * ($carfee + $insurancefee);
        /************************************************************************/
        // 新增預約紀錄
        $result=Rental::insert_Rental($memberId,$car_id,$startDateTime,$endDateTime,$location_id,$location_id,$insurance_id,$total_amount,$method);

        session(['web_status'=>'success']);
        session(['web_status_description' => '預約成功，可到會員管理專區確認，與付款']);
        return response()->json([
        'status' => 'success',
        'message' => '預約成功，可到會員管理專區確認，與付款',
        ]);
    }
}

