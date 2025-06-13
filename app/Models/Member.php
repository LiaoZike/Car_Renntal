<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model{
    use HasFactory;
    protected function insert_member($google_id, $gmail, $phone){
        return DB::insert('INSERT INTO member (google_id, gmail, phone) VALUES (?, ?, ?)', [
            $google_id,
            $gmail,
            $phone,
        ]);
    }
    protected function update_phone($gmail, $phone){
        return DB::update('UPDATE member SET phone = ? WHERE gmail = ?', [$phone, $gmail]);
    }
    protected function check_member($gmail){
        $member = DB::select('SELECT * FROM member WHERE gmail = ?', [$gmail]);
        return !empty($member); // 如果有結果，返回 true，否則返回 false
    }
    protected function check_phone_exists($phone){
        $member = DB::select('SELECT * FROM member WHERE phone = ?', [$phone]);
        return !empty($member);
    }
    protected function search_member_phone($gmail){
        $phone = DB::select('SELECT phone FROM member WHERE gmail = ?', [$gmail]);
        return $phone[0]->phone; // 如果有結果，返回 true，否則返回 false
    }
    protected function search_member_id($gmail){
        $id = DB::select('SELECT member_id FROM member WHERE gmail = ?', [$gmail]);
        return $id[0]->member_id; // 如果有結果，返回 true，否則返回 false
    }


    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
