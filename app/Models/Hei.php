<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hei extends Model
{

    public function getHeiTypes()
    {
        $heitype = DB::table('tbl_heis')->select('hei_it')->groupBy('hei_it')->get();

        echo json_encode($heitype,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
    public function getHeiRegions()
    {
        $heiregion = DB::table('tbl_heis')->select('hei_psg_region','hei_region_nir')->groupBy('hei_region_nir','hei_psg_region')->get();

        echo json_encode($heiregion,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
    public function getHeiProvinces($heiregion)
    {
        if ($heiregion==15) {
            $heiprovince = DB::table('tbl_heis')->select('hei_psg_region','hei_prov_name','hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name','hei_psg_region','hei_prov_code')->get();
        }else{
            $heiprovince = DB::table('tbl_heis')->select('hei_psg_region','hei_prov_name','hei_prov_code')->where('hei_psg_region', $heiregion)->groupBy('hei_prov_name','hei_psg_region','hei_prov_code')->get();
        }

        echo json_encode($heiprovince,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public function searchHeiName($heiname)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_shortname', 'like', '%' . $heiname . '%')->get();

        echo json_encode($hei,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public function getHeiInfo($heiuii)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->addSelect('hei_prov_name')->addSelect('hei_email')->addSelect('hei_telno')->addSelect('hei_focal')->addSelect('hei_focal_contact')->addSelect('hei_focal_email')->where('hei_uii', '=', $heiuii)->get();

        echo json_encode($hei,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public function getCourses($heiuii)
    {
        $hei = DB::table('tbl_registry')->select('course_name')->addSelect('course_code')->where('hei_uii', '=', $heiuii)->get();

        echo json_encode($hei,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public static function showHei($heiregion, $heiprov, $heitype)
    {
        if ($heitype == '') {
            $hei_it = '';
        }
        if ($heitype == 1) {
            $hei_it = 'SUC';
        }
        if ($heitype == 2) {
            $hei_it = 'LUC';
        }
        if ($heitype == 3) {
            $hei_it = 'Private HEI';
        }


        $hei = DB::table('tbl_heis')->select('hei_region_nir')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_it', 'like', '%' . $hei_it . '%')->where('hei_prov_code', '=', $heiprov)->get();

        echo json_encode($hei,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
