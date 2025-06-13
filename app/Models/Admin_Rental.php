<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_Rental extends Model{
    use HasFactory;
    protected function count_rental_status() {
            $base = DB::connection('mariadb_admin')->table('rental')
            ->select('rental_status', DB::raw('COUNT(*) as total'))
            ->groupBy('rental_status')
            ->pluck('total', 'rental_status')
            ->toArray();

        return array_merge($base, [
            'active_not_started' => DB::connection('mariadb_admin')->table('rental')
                ->where('rental_status', 'active')
                ->where('start_date', '>', now())
                ->count(),
            'active_ongoing' => DB::connection('mariadb_admin')->table('rental')
                ->where('rental_status', 'active')
                ->where('start_date', '<=', now())
                ->count(),
            'total' => array_sum($base),
        ]);
    }
    protected function search_order(){
        $results = DB::connection('mariadb_admin')->select("
        SELECT
            rental.rental_id AS id,
            member.phone,
            member.gmail,
            CONCAT(model.brand, ' ', model.model_name) AS full_model_name,
            model.car_type,
            model.fuel_type,
            model.engine_cc,
            model.transmission,
            model.image_url,
            car.plate_number,
            car.daily_fee,
            car.late_fee,
            car.seat_num,
            location.loc_name,
            rental.start_date,
            rental.end_date,
            rental.amount AS total_cost,
            rental.rental_status,
            rental.method,
            rental.payment_status,
            insurance.insurance_id,
            insurance.ins_name,
            insurance.coverage,
            insurance.ins_fee
        FROM rental
        JOIN member ON rental.member_id = member.member_id
        JOIN car ON rental.car_id = car.car_id
        JOIN model ON car.model_id = model.model_id
        JOIN location ON rental.pickup_loc = location.loc_id
        JOIN insurance ON rental.insurance_id = insurance.insurance_id
    ");
        return $results;
    }

    protected function search_Expired_order(){
        $results = DB::connection('mariadb_admin')->select("
        SELECT
            rental.rental_id AS id,
            member.phone,
            member.gmail,
            CONCAT(model.brand, ' ', model.model_name) AS full_model_name,
            car.plate_number,
            location.loc_name,
            rental.start_date,
            rental.end_date,
            rental.rental_status,
            rental.payment_status
        FROM rental
        JOIN member ON rental.member_id = member.member_id
        JOIN car ON rental.car_id = car.car_id
        JOIN model ON car.model_id = model.model_id
        JOIN location ON rental.pickup_loc = location.loc_id
        JOIN insurance ON rental.insurance_id = insurance.insurance_id
        WHERE rental.end_date < NOW()
          AND rental.rental_status IN ('active', 'pending')
    ");
        return $results;
    }

    protected function search_order_status($order_id){
        $result = DB::select('SELECT rental_status FROM rental WHERE rental_id = ?', [$order_id]);
        return $result ? $result[0]->rental_status : null;
    }
    protected function update_order($order_id,$insurance_id,$payment_method,$payment_status,$order_status,$total_amount){
        return DB::connection('mariadb_admin')->update('UPDATE rental SET insurance_id = ? , method = ? , payment_status = ? , rental_status = ? , amount = ? WHERE rental_id = ?', [$insurance_id,$payment_method,$payment_status,$order_status, $total_amount,$order_id]);
    }
    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
