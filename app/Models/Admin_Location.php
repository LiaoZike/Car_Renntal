<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_Location extends Model{
    use HasFactory;
    protected function search_all(){
        $locations = DB::connection('mariadb_admin')->table('location')->get();
        return $locations;
    }
    
    protected static function addLocation($data){
        return DB::connection('mariadb_admin')->table('location')->insert($data);
    }

    protected static function findLocation($id){
        return DB::connection('mariadb_admin')->table('location')->where('loc_id', $id)->first();
    }

    protected static function updateLocation($data){
        return DB::connection('mariadb_admin')->table('location')
            ->where('loc_id', $data['loc_id'])
            ->update([
                'loc_name' => $data['loc_name'],
                'city' => $data['city'],
                'district' => $data['district'],
                'address' => $data['address'],
            ]);
    }

    protected static function deleteLocation($id){
        return DB::connection('mariadb_admin')->table('location')->where('loc_id', $id)->delete();
    }
    

    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
