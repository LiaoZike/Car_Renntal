<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model{
    use HasFactory;
    protected function search_all(){
        $locations = DB::table('location')
            ->select('loc_id', 'loc_name')
            ->get();
        return $locations;
    }


    protected $fillable = [
    ];

    protected $hidden = [
    ];

}
