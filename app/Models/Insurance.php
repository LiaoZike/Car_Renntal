<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Insurance extends Model{
    use HasFactory;
    protected function search_all(){
        $insurances = DB::table('insurance')
            ->select('*')
            ->get();
        return $insurances;
    }
    protected function check_insurance_available($insurance_id){
        $result = DB::select('SELECT insurance_id FROM insurance WHERE insurance_id = ?', [$insurance_id]);
        return !empty($result);
    }


    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
