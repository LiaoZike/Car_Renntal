<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

//HTTP進來時驗證Token
class VerifyToken{
    public function handle(Request $request, Closure $next){
        $token = session('jwt_token');
        if (!$token) {
            return redirect()->route('admin.login.page');
        }
        session(['jwt_token' => session('jwt_token')]); // 2025/05/06修正BUG
        if(env('ALL_AUTH_AGAIN')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer $token"
                ])->get(env('AUTH_URL') . '/admin/verify');

                if ($response->successful()) {
                    session(['admin_free_retry' => true]);
                    return $next($request);
                } else {
                    session(['admin_free_retry' => false]);
                    return redirect()->route('admin.login.page');
                }
            } catch (\Exception $e) {
                return redirect()->route('admin.login.page');
            }
        }else{
            return $next($request);
        }
    }
}
