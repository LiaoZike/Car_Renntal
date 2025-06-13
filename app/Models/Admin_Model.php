<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_Model extends Model{
    use HasFactory;
    protected function search_all() {
        $results = DB::connection('mariadb_admin')->table('model')
        ->leftJoin('car', 'model.model_id', '=', 'car.model_id')
        ->select(
            'model.model_id',
            'model.brand',
            'model.model_name',
            'model.car_type',
            'model.fuel_type',
            'model.engine_cc',
            'model.transmission',
            'model.image_url',
            'car.car_id',
            'car.vin',
            'car.plate_number',
            'car.daily_fee',
            'car.late_fee',
            'car.year_made',
            'car.seat_num',
            'car.color',
            'car.mileage',
            'car.car_status',
            'car.notes'
        )
        ->get();
        return $results;
    }
    protected static function addModel($data) {
        return DB::connection('mariadb_admin')->table('model')->insert($data);
    }
    protected static function deleteModel($id){
        return DB::connection('mariadb_admin')->table('model')->where('model_id', $id)->delete();
    }
    protected static function find_Model($id){
        return DB::connection('mariadb_admin')->table('model')->where('model_id', $id)->first();
    }
    protected static function updateModel($data){    
        return DB::connection('mariadb_admin')->table('model')
            ->where('model_id', $data['model_id'])
            ->update([
                'brand' => $data['brand'],
                'model_name' => $data['model_name'],
                'car_type' => $data['car_type'],
                'fuel_type' => $data['fuel_type'],
                'engine_cc' => $data['engine_cc'],
                'transmission' => $data['transmission'],
                'image_url' => $data['image_url'],
            ]);
    }
    
    protected static function addCar($data) {
        return DB::connection('mariadb_admin')->table('car')->insert($data);
    }
    protected static function deleteCar($id){
        return DB::connection('mariadb_admin')->table('car')->where('car_id', $id)->delete();
    }
    protected static function find_Car($id){
        return DB::connection('mariadb_admin')->table('car')->where('car_id', $id)->first();
    }
    protected static function updateCar($data){    
        return DB::connection('mariadb_admin')->table('car')
            ->where('car_id', $data['car_id'])
            ->update([
                'plate_number' => $data['plate_number'],
                'vin' => $data['vin'],
                'loc_id' => $data['loc_id'],
                'daily_fee' => $data['daily_fee'],
                'late_fee' => $data['late_fee'],
                'year_made' => $data['year_made'],
                'seat_num' => $data['seat_num'],
                'color' => $data['color'],
                'mileage' => $data['mileage'],
                'car_status' => $data['car_status'],
                'notes' => $data['notes']
            ]);
    }
    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
