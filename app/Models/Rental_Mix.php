<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rental_Mix extends Model{
    use HasFactory;
    protected function selectAvailableCars($locationId,$noDateLimit, $startDate, $endDate,$carTypes,$fuel_type=null,$seats=0,$min_value=0,$max_value=8000){
        /*******
            SELECT c.*, m.brand, m.model_name
            FROM car c
            JOIN model m ON c.model_id = m.model_id
            LEFT JOIN rental r
              ON c.car_id = r.car_id
              AND r.rental_status IN ('pending', 'active', 'completed')
              AND NOT (
                r.start_date <= '2025-04-18 18:00'
                AND r.end_date >= '2025-04-20 09:00'
              ) -- 確保此範圍內的車輛不被選中
            WHERE c.loc_id = 1
              AND c.car_status = 'available'
              AND r.rental_id IS NULL;
         *******/
        $query = DB::table('car as c')
            ->join('model as m', 'c.model_id', '=', 'm.model_id')
            ->where('c.loc_id', $locationId)
            ->where('c.car_status', 'available')
            ->whereBetween('c.daily_fee', [$min_value, $max_value]);

        if (!$noDateLimit) {
            // 有選日期，要排除租期衝突
            $query = $query->leftJoin('rental as r', function ($join) use ($startDate, $endDate) {
                $join->on('c.car_id', '=', 'r.car_id')
                    ->whereIn('r.rental_status', ['pending', 'active', 'completed'])
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereRaw('DATE(DATE_SUB(r.start_date, INTERVAL 1 DAY)) <= ?', [$endDate])
                            ->whereRaw('DATE(DATE_ADD(r.end_date, INTERVAL 1 DAY)) >= ?', [$startDate]);
                    });
            })
                ->whereNull('r.rental_id');
        }

        // 如果有選擇車種，加入條件
        if (!empty($carTypes) && !in_array('Any', $carTypes)) {
            $query->whereIn('m.car_type', $carTypes);
        }
        if (!empty($fuel_type) && !in_array('Any', $fuel_type)) {
            $query->whereIn('m.fuel_type', $fuel_type);
        }
        if($seats!=0){
            $query->where('c.seat_num','>=', $seats);
        }
        return $query->select('c.*', 'm.*')
            ->get();

    }


    public static function checkCarID($id){
        $car = DB::table('car as c')
            ->where('c.car_id', $id)
            ->first();

        if (!$car) {
            return "ERROR";
        }

        return $car;
    }

    /**
     * 查詢指定車輛的所有租用日期
     */
    public static function getCarRentalDates($carId){
        $today = Carbon::today(); // 今天的日期
        $rentedDates = DB::table('rental')
            ->where('car_id', $carId)
            ->where('rental_status', '!=', 'cancelled') // 可根據需要篩選不同的狀態
            ->whereDate('end_date', '>=', $today) // 確保結束日期不小於今天
            ->get([
                DB::raw('DATE(start_date) as start_date'),
                DB::raw('DATE(end_date) as end_date')
            ])
            ->map(function ($item) {
                return [
                    'start_date' => Carbon::parse($item->start_date)->subDay()->toDateString(), // 減一天
                    'end_date' => Carbon::parse($item->end_date)->addDay()->toDateString(),     // 加一天
                ];
            });
        return $rentedDates;
    }
    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
