<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hei extends Model{

    public function getHeiTypes()
    {
        $heitype = DB::table('tbl_heis')->select('hei_it')->
        groupBy('hei_it')->get();

        echo json_encode($heitype);
    }
    public function getHeiRegions()
    {
        $heiregion = DB::table('tbl_heis')->select('hei_region_nir')->
        groupBy('hei_region_nir')->get();

        echo json_encode($heiregion);
    }
    public function getHeiProvinces($heiregion)
    {
        if($heiregion==''){
            $hei_region_nir = '';
        }
        if($heiregion==1){
            $hei_region_nir = '01 - Ilocos Region';
        }
        if($heiregion==2){
            $hei_region_nir = '02 - Cagayan Valley';
        }
        if($heiregion==3){
            $hei_region_nir = '03 - Central Luzon';
        }
        if($heiregion==4){
            $hei_region_nir = '04 - CALABARZON';
        }
        if($heiregion==5){
            $hei_region_nir = '05 - Bicol Region';
        }
        if($heiregion==6){
            $hei_region_nir = '06 - Western Visayas';
        }
        if($heiregion==7){
            $hei_region_nir = '07 - Central Visayas';
        }
        if($heiregion==8){
            $hei_region_nir = '08 - Eastern Visayas';
        }
        if($heiregion==9){
            $hei_region_nir = '09 - Zamboanga Peninsula';
        }
        if($heiregion==10){
            $hei_region_nir = '10 - Northern Mindanao';
        }
        if($heiregion==11){
            $hei_region_nir = '11 - Davao Region';
        }
        if($heiregion==12){
            $hei_region_nir = '12 - Soccsksargen';
        }
        if($heiregion==13){
            $hei_region_nir = '13 - National Capital Region';
        }
        if($heiregion==14){
            $hei_region_nir = '13 - NCR';
        }
        if($heiregion==15){
            $hei_region_nir = '14 - CAR';
        }
        if($heiregion==16){
            $hei_region_nir = '14 - Cordillera Administrative Region';
        }
        if($heiregion==17){
            $hei_region_nir = '15 - BARMM';
        }
        if($heiregion==18){
            $hei_region_nir = '15A - BARMM';
        }
        if($heiregion==19){
            $hei_region_nir = '15B - BARMM';
        }
        if($heiregion==20){
            $hei_region_nir = '16 - Caraga';
        }
        if($heiregion==21){
            $hei_region_nir = '17 - MIMAROPA';
        }
        
        $heiprovince = DB::table('tbl_heis')->select('hei_prov_name')->
        groupBy('hei_prov_name')->
        where('hei_region_nir',$hei_region_nir)->
        get();

        echo json_encode($heiprovince);
    }

    public function searchHeiName($heiname)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->
        addSelect('hei_shortname')->
        addSelect('hei_it')->
        addSelect('hei_ct')->
        where('hei_shortname','like','%'.$heiname.'%')->
        get();

        echo json_encode($hei);
    }

    public function getHeiInfo($heiuii)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->
        addSelect('hei_shortname')->
        addSelect('hei_it')->
        addSelect('hei_ct')->
        addSelect('hei_prov_name')->
        addSelect('hei_email')->
        addSelect('hei_telno')->
        addSelect('hei_focal')->
        addSelect('hei_focal_contact')->
        addSelect('hei_focal_email')->
        where('hei_uii','=',$heiuii)->get();

        echo json_encode($hei);
    }

    public function getCourses($heiuii)
    {
        $hei = DB::table('tbl_registry')->select('course_name')->
        addSelect('course_code')->
        where('hei_uii','=',$heiuii)->
        get();

        echo json_encode($hei);
    }

    public static function showHei($heitype, $heiregion, $heiprov)
    {
        if($heitype==''){
            $hei_it = '';
        }
        if($heitype==1){
            $hei_it = 'CHED Supervised Inst.';
        }
        if($heitype==2){
            $hei_it = 'LUC';
        }
        if($heitype==3){
            $hei_it = "Other Gov't Sch.";
        }
        if($heitype==4){
            $hei_it = 'Private HEI';
        }
        if($heitype==5){
            $hei_it = 'Special HEI';
        }
        if($heitype==6){
            $hei_it = 'SUC';
        }
        

        if($heiregion==''){
            $hei_region_nir = '';
        }
        if($heiregion==1){
            $hei_region_nir = '01 - Ilocos Region';
        }
        if($heiregion==2){
            $hei_region_nir = '02 - Cagayan Valley';
        }
        if($heiregion==3){
            $hei_region_nir = '03 - Central Luzon';
        }
        if($heiregion==4){
            $hei_region_nir = '04 - CALABARZON';
        }
        if($heiregion==5){
            $hei_region_nir = '05 - Bicol Region';
        }
        if($heiregion==6){
            $hei_region_nir = '06 - Western Visayas';
        }
        if($heiregion==7){
            $hei_region_nir = '07 - Central Visayas';
        }
        if($heiregion==8){
            $hei_region_nir = '08 - Eastern Visayas';
        }
        if($heiregion==9){
            $hei_region_nir = '09 - Zamboanga Peninsula';
        }
        if($heiregion==10){
            $hei_region_nir = '10 - Northern Mindanao';
        }
        if($heiregion==11){
            $hei_region_nir = '11 - Davao Region';
        }
        if($heiregion==12){
            $hei_region_nir = '12 - Soccsksargen';
        }
        if($heiregion==13){
            $hei_region_nir = '13 - National Capital Region';
        }
        if($heiregion==14){
            $hei_region_nir = '13 - NCR';
        }
        if($heiregion==15){
            $hei_region_nir = '14 - CAR';
        }
        if($heiregion==16){
            $hei_region_nir = '14 - Cordillera Administrative Region';
        }
        if($heiregion==17){
            $hei_region_nir = '15 - BARMM';
        }
        if($heiregion==18){
            $hei_region_nir = '15A - BARMM';
        }
        if($heiregion==19){
            $hei_region_nir = '15B - BARMM';
        }
        if($heiregion==20){
            $hei_region_nir = '16 - Caraga';
        }
        if($heiregion==21){
            $hei_region_nir = '17 - MIMAROPA';
        }

                    
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->
        addSelect('hei_prov_name')->
        addSelect('hei_shortname')->
        addSelect('hei_it')->
        addSelect('hei_ct')->
        where('hei_it','=',$hei_it)->
        where('hei_region_nir','=',$hei_region_nir)->
        where('hei_prov_name','=',$heiprov)->
        get();

        echo json_encode($hei);
    }
}