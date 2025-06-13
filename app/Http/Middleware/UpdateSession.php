<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UpdateSession{
    public function handle($request, Closure $next)    {
        // 檢查用戶是否已經登入並且 session 中存在 'user_data'
        if (session()->has('user_data')) {
            $userData = session('user_data');
            $lastActivity = $userData['last_activity'];
            $timeout = env('session_timeout_min',60); // 分鐘
            // 檢查 'last_activity' 是否超過設定的過期時間
            if (abs(now()->diffInMinutes($lastActivity)) > $timeout) {
                session()->forget('user_data'); // 清除 session
                session(['web_status'=>'error']);
                session(['web_status_description' => '由於你太久未操作，系統幫你自動登出，請重新登入']);
                return redirect()->route('home')->with('error', 'Session expired. Please log in again.');
            }
            // 更新 'last_activity' 為當前時間
            session(['user_data.last_activity'=>now()]);
        }

        return $next($request);
    }

}
