<?php

namespace App\Helpers;

class ParamHelper
{
    public static function buildQ($city=null, $country=null){
        $q="";

        if($city!==null){
            $q.= $city . ",";
        }
        if($country){
            $q.= $country;
        }

        return $q;
    }
    public static function buildDt($date=null){
        $dt = "";
        if($date!==null){
            $dt = $date;
        }
        return $dt;
    }

}
