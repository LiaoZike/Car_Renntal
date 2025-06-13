<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use MongoDB\Driver\Session;
use function PHPUnit\Framework\isEmpty;
use Carbon\Carbon;

class AuthController{
    /**************************************/
    /* 跳轉Google登入頁面                  */
    /**************************************/
    public function redirectToProvider(){  //登入動作
        session()->forget('user_data');
        session()->flush();
        return Socialite::driver('google')->redirect();

    }

    /**************************************/
    /* Google驗證完(1.跳轉註冊 2.跳轉首頁)   */
    /**************************************/
    public function handleProviderCallback(Request $request){
        if (session()->has('user_data') || empty($request->all())) {
            return redirect()->route('google.auth.page');
        }

        $user = Socialite::driver('google')->stateless()->user();
        $datas = [
            'google_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => "",
            'verify_phone'=>"",
    //        'avatar' => $user->avatar, //頭像
    //        'token' => $user->token,
            'expires_in' => now()->addSeconds($user->expiresIn)->toDateTimeString(),
            'active'=>false, //標記是否是儲存資料庫帳號密碼 1:True正是帳號 0:False需要電話驗證
            'verify_code'=>"",  //電話驗證碼
            'verify_expires'=>now(),  //電話驗證碼過期時間
            'verify_code_retry'=>0,
            'last_activity' => now(),  // middleware使用 紀錄上次操作時間
        ];

        session(['user_data' => $datas]);
        if(Member::check_member($user->email)){
            session(['user_data.active'=>true]);
            session(['user_data.phone'=>Member::search_member_phone($user->email)]);
            session(['web_status'=>'success']);
            session(['web_status_description' => '登入成功']);
            return redirect()->route('home');
        }else{
            session(['user_data.active'=>false]);
            return redirect()->route('google.auth.verify_phone');
        }
    }

    /**************************************/
    /* 跳轉註冊介面                        */
    /**************************************/
    public function verify_phone(){
        if (!session()->has('user_data')) {
            return redirect()->route('google.auth.page');
        }
        if(session('user_data.active')==true){
            return redirect()->route('home');
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
        return view('home.verify_member',compact('remaining'));
    }

    /**************************************/
    /* 註冊動作(驗證&寫入資料庫)             */
    /**************************************/
    public function verify_phone_post(Request $request){ //驗證註冊資訊
        if(session('user_data.active')===true){
            return redirect()->route('home');
        }
        $requests=$request->all();
        session(['user_data.verify_code_retry'=> session('user_data.verify_code_retry')+1]);
        if($requests['phone']==session('user_data.verify_phone')&&
            $requests['code']==session('user_data.verify_code')&&
            now()->lessThan(session('user_data.verify_expires'))&&
                session('user_data.verify_code_retry') <= 3
        ){
            Member::insert_member(session('user_data.google_id'),$requests['gmail'],$requests['phone']);
            session(['user_data.phone'=>session('user_data.verify_phone')]);
            session(['user_data.active'=>true]);
            return redirect()->route('home') // 這裡的 `home` 是主畫面的路由名稱，根據你的設定調整
            ->with([
                'web_status' => 'success',
                'web_status_description' => '註冊成功，歡迎！'
            ]);
        };
        if(session('user_data.verify_code_retry')>3){
            session(['user_data.verify_phone' => ""]);
            session(['user_data.verify_code'=>""]);
            return redirect()->route('google.auth.verify_phone')
                ->with([
                    'web_status' => 'error',
                    'web_status_description' => '驗證碼輸入錯誤多次，請重新取得驗證碼。'
                ])
                ->withInput();
        }

        return redirect()->route('google.auth.verify_phone')
            ->with([
                'web_status' => 'error',
                'web_status_description' => '驗證碼錯誤，請重新輸入。'
            ])
            ->withInput();
    }

    public function sendCode(Request $request){
        session(['user_data.verify_code_retry'=>0]);
        if (!session()->has('user_data')) {
            session(['web_status'=>'error']);
            session(['web_status_description' => '登入期限過期，請重新登入']);
            session(['user_data.verify_code'=>0]);
            return response()->json([
                'status' => 'session_timeout',
                'message' => ''
            ]);
        }else{
            $userData = session('user_data');
            $lastActivity = $userData['last_activity'];
            // 檢查 'last_activity' 是否超過設定的過期時間
            if (abs(now()->diffInMinutes($lastActivity)) > env('session_timeout_min',60)) {
                session()->forget('user_data'); // 清除 session
                session(['web_status'=>'error']);
                session(['web_status_description' => '登入期限過期，請重新登入']);
                return response()->json([
                    'status' => 'session_timeout',
                    'message' => ''
                ]);
            }
            //有效期限內
            if(Member::check_phone_exists($request->phone)){
                return response()->json([
                    'status' => 'error',
                    'message' => '該手機號碼已被註冊'
                ]);
            }
            session(['user_data.last_activity' => now()]);
        }
        $request->validate([
            'phone' => 'required|regex:/^[0-9]{10,}$/'
        ]);
        $phone = $request->phone;

        //[後端防護多次請求]
        $lastSendTime = session('last_send_code');
        if (!empty($lastSendTime)) {
            $secondsSinceLastSend = abs(now()->diffInSeconds($lastSendTime));
            if ($secondsSinceLastSend < 60) {
                return response()->json([
                    'status' => 'fail',
                    'message' => '請勿重複發送，請稍後再嘗試！'
                ]);
            }
        }

        session(['last_send_code' => now()]);
        session(['user_data.verify_phone' => $phone]);
        session(['user_data.verify_code'=>"123456"]);  //刷新驗證碼
        session(['user_data.verify_expires' => now()->addMinutes(30)]); //驗證碼期限
        session(['user_data.last_activity'=>now()]); //刷新最後操作時間

        return response()->json([
            'status' => 'success',
            'message' => '驗證碼已發送'
        ]);
    }

    /**************************************/
    /* 登出動作             */
    /**************************************/
    public function logout()    {
        // 清除所有 Session
        session()->forget('user_data');

        // 跳轉回登入頁或首頁
        return redirect()->route('home')
            ->with([
                'web_status' => 'success',
                'web_status_description' => '登出成功'
            ]);
    }


}

