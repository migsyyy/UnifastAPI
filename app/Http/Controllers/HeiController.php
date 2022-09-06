<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hei;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\DB;


class HeiController extends Controller
{
    public function disbursementInfoTES($region = '', $province = '', $heiid = '')
    {
        // echo $heiid;
        if ($region) {
            $region = str_pad($region, 2, '0', STR_PAD_LEFT);
        }
        if (!$region) {
            $this->getRegions();
        } elseif (!$province && $region) {
            if (is_numeric($region)) {
                $this->getProvinces($region);
            }
            // echo json_encode([]);
        } elseif (!$heiid && $region && $province) {
            if (is_numeric($region) && is_numeric($province)) {
                $this->getHeis($region, $province);
            }
            // echo json_encode([]);
        } elseif ($heiid && $region && $province) {
            if (is_numeric($heiid) && is_numeric($region) && is_numeric($province)) {

                if ($region == 15) {
                    $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
                } else {
                    $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $region)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
                }
                if ($province <= count($heiprovince)) {
                    $heiprovcode = $heiprovince[$province - 1]->hei_prov_code;
                    $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_prov_code', '=', $heiprovcode)->orderBy('hei_shortname')->get();
                    foreach ($heis as $key => $hei) {
                        // $hei->seq_id = $key + 1;
                        if ($key + 1 == $heiid) {
                            $hei_uii = $hei->hei_uii;
                        }
                    }
                    if (isset($hei_uii)) {

                        $esgppa = DB::table("tbl_esgppa_2021_2022")
                            ->select("uid", "hei_uii", "hei_name", "date_disbursed")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $pnsl = DB::table("tbl_pnsl_2021_2022")
                            ->select("uid", "hei_uii", "hei_name", "date_disbursed")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $lista = DB::table("tbl_lista_2021_2022")
                            ->select("uid", "hei_uii", "hei_name", "date_disbursed")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $union = $esgppa->union($pnsl)->union($lista);
                        $dis_info = DB::table("tbl_heis")
                            ->selectRaw('union_epl.hei_uii,
                                union_epl.hei_name,
                                union_epl.semester,
                                tbl_heis.hei_it,
                                tbl_heis.hei_focal,
                                tbl_heis.hei_focal_contact,
                                tbl_heis.hei_focal_email,
                                CASE
                                    tbl_heis.hei_it
                                    WHEN "PRIVATE HEI" THEN 30000 * COUNT(*)
                                    WHEN "SUC" THEN 20000 * COUNT(*)
                                    WHEN "LUC" THEN 20000 * COUNT(*)
                                END AS amount,
                                date_disbursed,
                                COUNT(*) AS bene')
                            ->joinSub($union, 'union_epl', function ($join) {
                                $join->on('tbl_heis.hei_uii', '=', 'union_epl.hei_uii');
                            })
                            ->groupBy('union_epl.hei_uii', 'union_epl.hei_name', 'tbl_heis.hei_it', 'date_disbursed','semester')
                            ->get();

                        echo json_encode($dis_info);
                    }
                }
            }
        }
    }
    public function disbursementInfoTDP($region = '', $province = '', $heiid = '')
    {
        // echo $heiid;
        if ($region) {
            $region = str_pad($region, 2, '0', STR_PAD_LEFT);
        }
        if (!$region) {
            $this->getRegions();
        } elseif (!$province && $region) {
            if (is_numeric($region)) {
                $this->getProvinces($region);
            }
            // echo json_encode([]);
        } elseif (!$heiid && $region && $province) {
            if (is_numeric($region) && is_numeric($province)) {
                $this->getHeis($region, $province);
            }
            // echo json_encode([]);
        } elseif ($heiid && $region && $province) {
            if (is_numeric($heiid) && is_numeric($region) && is_numeric($province)) {

                if ($region == 15) {
                    $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
                } else {
                    $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $region)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
                }
                if ($province <= count($heiprovince)) {
                    $heiprovcode = $heiprovince[$province - 1]->hei_prov_code;
                    $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_prov_code', '=', $heiprovcode)->orderBy('hei_shortname')->get();
                    foreach ($heis as $key => $hei) {
                        // $hei->seq_id = $key + 1;
                        if ($key + 1 == $heiid) {
                            $hei_uii = $hei->hei_uii;
                        }
                    }
                    if (isset($hei_uii)) {

                        $tdp = DB::table("tbl_chedtdp_2021_2022")
                            ->select("uid", "hei_uii", "hei_name", "date_disbursed","semester")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        // $union = $esgppa->union($pnsl)->union($lista);
                        $dis_info = DB::table("tbl_heis")
                            ->selectRaw('union_epl.hei_uii,
                                union_epl.hei_name,
                                union_epl.semester,
                                tbl_heis.hei_it,
                                tbl_heis.hei_focal,
                                tbl_heis.hei_focal_contact,
                                tbl_heis.hei_focal_email,
                                tbl_heis.hei_it,
                                7500 * COUNT(*) AS
                                amount,
                                date_disbursed,
                                COUNT(*) AS bene')
                            ->joinSub($tdp, 'union_epl', function ($join) {
                                $join->on('tbl_heis.hei_uii', '=', 'union_epl.hei_uii');
                            })
                            ->groupBy('union_epl.hei_uii', 'union_epl.hei_name', 'tbl_heis.hei_it', 'date_disbursed','union_epl.semester')
                            ->get();

                        echo json_encode($dis_info);
                    }
                }
            }
        }
    }

    public function getHeiTypes()
    {
        $heitype = DB::table('tbl_heis')->select('hei_it')->groupBy('hei_it')->get();

        echo json_encode($heitype, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    public function getProvinces($heiregion)
    {
        if (is_numeric($heiregion)) {
            $heiregion = str_pad($heiregion, 2, '0', STR_PAD_LEFT);
            if ($heiregion == 15) {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
            } else {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $heiregion)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
            }

            foreach ($heiprovince as $key => &$province) {
                $province->hei_prov_code = $key + 1;
            }

            echo json_encode($heiprovince, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function searchHeiName($heiname)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_shortname', 'like', '%' . $heiname . '%')->get();

        echo json_encode($hei, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getHeiInfo($heiuii)
    {
        $hei = DB::table('tbl_heis')->select('hei_region_nir')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->addSelect('hei_prov_name')->addSelect('hei_email')->addSelect('hei_telno')->addSelect('hei_focal')->addSelect('hei_focal_contact')->addSelect('hei_focal_email')->where('hei_uii', '=', $heiuii)->get();

        echo json_encode($hei, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getCourses($heiuii)
    {
        $hei = DB::table('tbl_registry')->select('course_name')->addSelect('course_code')->where('hei_uii', '=', $heiuii)->get();

        echo json_encode($hei, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getHeis($heiregion = '', $heiprov = '', $heitype = '')
    {

        if (is_numeric($heiregion) && is_numeric($heiprov)) {
            $heiprov = $heiprov - 1;
            $heiregion = str_pad($heiregion, 2, '0', STR_PAD_LEFT);

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
            if ($heiregion == 15) {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
            } else {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $heiregion)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->get()->toArray();
            }

            if ($heiprov < count($heiprovince)) {
                $heiprovcode = $heiprovince[$heiprov]->hei_prov_code;
                $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_it', 'like', '%' . $hei_it . '%')->where('hei_prov_code', '=', $heiprovcode)->orderBy('hei_shortname')->get();
                foreach ($heis as $key => &$hei) {
                    $hei->seq_id = $key + 1;
                }
                echo json_encode($heis, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
    }

    public function getRegions()
    {

        echo '[
            {
                "hei_psg_region": "01",
                "hei_region_nir": "01 - Ilocos Region"
            },
            {
                "hei_psg_region": "02",
                "hei_region_nir": "02 - Cagayan Valley"
            },
            {
                "hei_psg_region": "03",
                "hei_region_nir": "03 - Central Luzon"
            },
            {
                "hei_psg_region": "04",
                "hei_region_nir": "04 - CALABARZON"
            },
            {
                "hei_psg_region": "05",
                "hei_region_nir": "05 - Bicol Region"
            },
            {
                "hei_psg_region": "06",
                "hei_region_nir": "06 - Western Visayas"
            },
            {
                "hei_psg_region": "07",
                "hei_region_nir": "07 - Central Visayas"
            },
            {
                "hei_psg_region": "08",
                "hei_region_nir": "08 - Eastern Visayas"
            },
            {
                "hei_psg_region": "09",
                "hei_region_nir": "09 - Zamboanga Peninsula"
            },
            {
                "hei_psg_region": "10",
                "hei_region_nir": "10 - Northern Mindanao"
            },
            {
                "hei_psg_region": "11",
                "hei_region_nir": "11 - Davao Region"
            },
            {
                "hei_psg_region": "12",
                "hei_region_nir": "12 - Soccsksargen"
            },
            {
                "hei_psg_region": "13",
                "hei_region_nir": "13 - NCR"
            },
            {
                "hei_psg_region": "14",
                "hei_region_nir": "14 - CAR"
            },
            {
                "hei_psg_region": "15",
                "hei_region_nir": "15 - BARMM"
            },
            {
                "hei_psg_region": "16",
                "hei_region_nir": "16 - Caraga"
            },
            {
                "hei_psg_region": "17",
                "hei_region_nir": "17 - MIMAROPA"
            }
        ]';
    }
}
