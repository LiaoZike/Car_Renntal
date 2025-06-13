<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_Insurance extends Model{
    use HasFactory;
    
    protected function search_all(){
        $insurances = DB::connection('mariadb_admin')->table('insurance')
            ->select('*')
            ->get();
        return $insurances;
    }
    
    protected function find_ins($id){
        return DB::connection('mariadb_admin')->table('insurance')
            ->where('insurance_id', $id)
            ->first();
    }
    protected function updateDBInsurance($data){
        return DB::connection('mariadb_admin')->table('insurance')
            ->where('insurance_id', $data['insurance_id'])
            ->update([
                'ins_name' => $data['ins_name'],
                'ins_fee' => $data['ins_fee'],
                'coverage' => $data['coverage'],
            ]);
    }
    protected static function addInsurance($data){
        return DB::connection('mariadb_admin')->table('insurance')->insert([
            'ins_name' => $data['ins_name'],
            'ins_fee' => $data['ins_fee'],
            'coverage' => $data['coverage'],
        ]);
    }

    protected static function deleteInsurance($id){
        return DB::connection('mariadb_admin')->table('insurance')
            ->where('insurance_id', $id)
            ->delete();
    }

    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
