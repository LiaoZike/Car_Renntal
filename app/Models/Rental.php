<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Rental extends Model{
    use HasFactory;
    protected function insert_Rental($memberId,$car_id,$startDateTime,$endDateTime,$pick_loc,$drop_loc,$insurance_id,$total_amount,$method){
        return DB::insert('
        INSERT INTO rental (member_id, car_id, start_date, end_date, pickup_loc, drop_loc, insurance_id, rental_status,amount, method,created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ', [
            $memberId,
            $car_id,
            $startDateTime,
            $endDateTime,
            $pick_loc,
            $drop_loc,
            $insurance_id,
            'active',
//            'pending',
            $total_amount,
            $method,
            now()
        ]);
    }

    protected function search_car_fee($car_id){
        $fee = DB::select('SELECT daily_fee FROM car WHERE car_id = ?', [$car_id]);
        return $fee[0]->daily_fee; // 如果有結果，返回 true，否則返回 false
    }
    protected function search_insurance_fee($insurance_id){
        $fee = DB::select('SELECT ins_fee FROM insurance WHERE insurance_id = ?', [$insurance_id]);
        return $fee[0]->ins_fee; // 如果有結果，返回 true，否則返回 false
    }
    protected function search_member_order($member_id){
        $order = DB::select('
        SELECT
            rental.*,
            car.plate_number,
            model.engine_cc,
            model.brand,
            model.model_name,
            model.image_url,
            pickup_loc.loc_name AS pickup_location_name,
            drop_loc.loc_name AS drop_location_name,
            insurance.ins_name,
            insurance.coverage
        FROM rental
        JOIN car ON rental.car_id = car.car_id
        JOIN model ON car.model_id = model.model_id
        JOIN location AS pickup_loc ON rental.pickup_loc = pickup_loc.loc_id
        JOIN location AS drop_loc ON rental.drop_loc = drop_loc.loc_id
        JOIN insurance ON rental.insurance_id = insurance.insurance_id
            WHERE rental.member_id = ?
    ORDER BY rental.end_date DESC
    ', [$member_id]);

        return $order;
    }
    protected function search_order_owner($order_id){
        $result = DB::select('SELECT member_id FROM rental WHERE rental_id = ?', [$order_id]);
        return $result ? $result[0]->member_id : null;
    }
    protected function search_order_data($order_id){
        $result = DB::select('SELECT * FROM rental WHERE rental_id = ?', [$order_id]);
        return $result ? $result[0] : null;
    }
    protected static function update_order_status($order_id, $new_status){
        return DB::update('UPDATE rental SET rental_status = ? WHERE rental_id = ?', [
            $new_status,
            $order_id
        ]);
    }
    protected static function update_order_insurance($order_id, $new_insurance_id,$total_amount){
        return DB::update('UPDATE rental SET insurance_id = ? ,amount = ? WHERE rental_id = ?', [
            $new_insurance_id,
            $total_amount,
            $order_id
        ]);
    }
    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
