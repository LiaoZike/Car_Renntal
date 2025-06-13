<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminAuthController{
    public function login(Request $request){  //登入動作
        try {
            $username = $request->input('username');
            $password = $request->input('password');

            // 發送 HTTP 請求
            $response = Http::post(env('AUTH_URL').'/admin/login', [
                'username' => $username,
                'password' => $password,
            ]);
            // 檢查 HTTP 請求是否成功
            if ($response->successful()) {
                $data = $response->json();
                session()->forget('jwt_token');
                session()->invalidate();
                session(['username' => $username]);
                session(['jwt_token' => $data['access_token']]);
                session(['web_status'=>'success']);
                session(['web_status_description' => '登入成功']);
                return "OK";
            } else {
                // 當 HTTP 請求失敗時
                $data = $response->json();
                return $data['error'];
            }
        } catch (\Exception $e) {
            // 捕獲異常並返回錯誤訊息
            return "Error Connect to Server!";
        }
    }


    ##############################
    # 登出                        #
    ##############################
    public function logout(){
        try {
            $token = session('jwt_token'); // 取得 Laravel Session 內的 Token
            $response = Http::withHeaders([
                'Authorization' => "Bearer $token"
            ])->post(env('AUTH_URL').'/admin/logout');

            if ($response->successful()) {
//                session()->forget('admin_free_retry');
//                session()->forget('jwt_token');
//                session()->invalidate();
            }
            session(['web_status'=>'success']);
            session(['web_status_description' => '登出成功']);
            return redirect()->route('admin.login.page');
        } catch (\Exception $e) {
            session(['web_status'=>'error']);
            session(['web_status_description' => 'Server ERROR']);
            return redirect()->route('admin.login.page');
        }
    }



    public function verification(){  //驗證確認動作
        try {
            $token = session('jwt_token');
            if (!$token) {
                return redirect()->route('admin.login.page');
            }
            session(['jwt_token' => session('access_token')], env('SESSION_LIFETIME')); //2025/05/04 新增解決BUG
            $response = Http::withHeaders([
                'Authorization' => "Bearer $token"
            ])->get('http://127.0.0.1:5000/admin/verify');

            if ($response->successful()) {
                session(['admin_free_retry' => true]);
                return True;
            } else {
                session(['admin_free_retry' => false]);
                return False;
            }
        } catch (\Exception $e) {
            return False;
        }


    }


}

