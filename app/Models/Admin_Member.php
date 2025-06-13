<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_Member extends Model{
    use HasFactory;
    
    protected function search_all(){
        return DB::connection('mariadb_admin')->table('member')->get();
    }

    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
