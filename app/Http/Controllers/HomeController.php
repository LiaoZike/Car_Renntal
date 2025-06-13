<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Location;
use App\Models\Rental_Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class HomeController{
    protected $carTypeMapping = [
        'Compact' => '小型車',
        'Sedan' => '中大型房車',
        'SUV' => 'SUV',
        'MPV' => 'MPV',
    ];
    protected $fuelTypeMapping = [
        'Gasoline' => '汽油',
        'Hybrid' => '油電混合',
        'Electric' => '電動',
    ];
    protected function info_replace_chinese($cars){
        if(!empty($cars)){
            foreach ($cars as &$car) {
                $car->car_type = $this->carTypeMapping[$car->car_type] ?? $car->car_type;
                $car->fuel_type = $this->fuelTypeMapping[$car->fuel_type] ?? $car->fuel_type;
                $car->transmission = ($car->transmission)?"自排":"手排";
            }
        }
        return $cars;
    }

    public function home(){  //登入動作
        $lastActivity = Session('user_data');
        $locations=Location::search_all();
        $datas=[
            'lastActivity'=>$lastActivity,
            'locations'=>$locations
        ];
//        dump($lastActivity);
        return view('home.index',$datas);
    }
    public function search(){
        $locations=Location::search_all();
        $insurances=Insurance::search_all();
        $datas=[
            'first_show'=>true,
            'locations'=>$locations,
            'selectedTypes'=>["Any"],
            'selectedFuelTypes'=>["Any"],
            'insurances'=>$insurances,
            'noDateLimit'=>false
        ];
        return view('home.search',$datas);
    }
    public function search_post(Request $request){
        $default_daterange = Carbon::tomorrow()->format('Y-m-d') . ' - ' . Carbon::tomorrow()->addDay(3)->format('Y-m-d');
        /* Only copy data: */
        $daterange=$request->input('daterange',$default_daterange);
        if(empty($daterange)) $daterange=$default_daterange;
        $pickupTime=$request->input('pickupTime',"08:00");
        $returnTime=$request->input('returnTime',"17:00");
        /********************/
        $locationId = $request->input('location');
        $carTypes = $request->input('car_type', ["Any"]);
        $fuel_type = $request->input('fuel_type', ["Any"]);
        $min_value = $request->input('min_value',0);
        $max_value = $request->input('max_value',6000);
        $noDateLimit = $request->has('noDateLimit');
        $seats=$request->input('seats','0');
        $insurances=Insurance::search_all();
//        dd($request->all());
        [$startDate, $endDate] = explode(' - ', $daterange);
        $cars=Rental_Mix::selectAvailableCars($locationId,$noDateLimit,$startDate,$endDate,$carTypes,$fuel_type,$seats,$min_value,$max_value);
        $cars=$this->info_replace_chinese($cars);
        $locations=Location::search_all();
        $datas=[
            'cars'=>$cars,
            'locations'=>$locations,
            'selectedTypes'=>$carTypes,
            'selectedFuelTypes'=>$fuel_type,
            'seats'=>$seats,
            'min_value' => $min_value,
            'max_value' => $max_value,
            //only return.
            'daterange'=>$daterange,
            'pickupTime'=>$pickupTime,
            'returnTime'=>$returnTime,
            'insurances'=>$insurances,
            'noDateLimit'=>$noDateLimit

        ];
        return view('home.search', $datas);
    }
    public function notice(){
        return view('home.notice');
    }

}

