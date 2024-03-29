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
                    $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_prov_code', '=', $heiprovcode)
                        ->where('hei_focal', '!=', 'NO UNIFAST')
                        ->where('hei_focal', '!=', 'HEI CLOSED')
                        ->where('hei_focal', '!=', 'INACTIVE')
                        ->where('hei_focal', '!=', 'NO GRANTEES')
                        ->where('hei_focal', '!=', 'No TES grantees')
                        ->where('hei_focal', '!=', 'NOT OPERATIONAL')
                        ->where('hei_focal', '!=', '')
                        ->whereNotNull('hei_focal')
                        ->orderBy('hei_shortname')->get();
                    foreach ($heis as $key => $hei) {
                        // $hei->seq_id = $key + 1;
                        if ($key + 1 == $heiid) {
                            $hei_uii = $hei->hei_uii;
                        }
                    }
                    if (isset($hei_uii)) {

                        $esgppa = DB::table("tbl_esgppa_2021_2022")
                            ->selectRaw("uid, hei_uii, hei_name, date_disbursed, semester, '2021-2022' as ac_year")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $pnsl = DB::table("tbl_pnsl_2021_2022")
                            ->selectRaw("uid, hei_uii, hei_name, date_disbursed, semester, '2021-2022' as ac_year")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $lista = DB::table("tbl_lista_2021_2022")
                            ->selectRaw("uid, hei_uii, hei_name, date_disbursed, semester, '2021-2022' as ac_year")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $union = $esgppa->union($pnsl)->union($lista);

                        $hei_info = DB::table('tbl_heis')->selectRaw('
                        hei_name,
                        hei_focal,
                        hei_focal_contact,
                        hei_focal_email')->where('hei_uii', $hei_uii)->get()->toArray();
                        $dis_info = json_decode(json_encode($hei_info[0]), true);

                        $disbursements = DB::table("tbl_heis")
                            ->selectRaw('
                                union_epl.semester,
                                union_epl.ac_year,
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
                            ->groupBy('tbl_heis.hei_it', 'date_disbursed', 'semester', 'union_epl.ac_year')
                            ->get();
                        $dis_info['disbursements'] = json_decode(json_encode($disbursements), true);

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
                    $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_prov_code', '=', $heiprovcode)
                        ->where('hei_focal', '!=', 'NO UNIFAST')
                        ->where('hei_focal', '!=', 'HEI CLOSED')
                        ->where('hei_focal', '!=', 'INACTIVE')
                        ->where('hei_focal', '!=', 'NO GRANTEES')
                        ->where('hei_focal', '!=', 'No TES grantees')
                        ->where('hei_focal', '!=', 'NOT OPERATIONAL')
                        ->where('hei_focal', '!=', '')
                        ->whereNotNull('hei_focal')
                        ->orderBy('hei_shortname')->get();
                    foreach ($heis as $key => $hei) {
                        // $hei->seq_id = $key + 1;
                        if ($key + 1 == $heiid) {
                            $hei_uii = $hei->hei_uii;
                        }
                    }
                    if (isset($hei_uii)) {

                        $tdp = DB::table("tbl_chedtdp_2021_2022")
                            ->selectRaw("uid, hei_uii, hei_name, date_disbursed, semester, '2021-2022' as ac_year")
                            ->where("in_disbursement", "=", "PAID")
                            ->where("hei_uii", "=", $hei_uii);

                        $hei_info = DB::table('tbl_heis')->selectRaw('
                            hei_name,
                            hei_focal,
                            hei_focal_contact,
                            hei_focal_email')->where('hei_uii', $hei_uii)->get()->toArray();
                        $dis_info = json_decode(json_encode($hei_info[0]), true);

                        $disbursements = DB::table("tbl_heis")
                            ->selectRaw('
                                union_epl.semester,
                                union_epl.ac_year,
                                tbl_heis.hei_it,
                                7500 * COUNT(*) AS
                                amount,
                                date_disbursed,
                                COUNT(*) AS bene')
                            ->joinSub($tdp, 'union_epl', function ($join) {
                                $join->on('tbl_heis.hei_uii', '=', 'union_epl.hei_uii');
                            })
                            ->groupBy('tbl_heis.hei_it', 'date_disbursed', 'union_epl.semester', 'union_epl.ac_year')
                            ->get();
                        $dis_info['disbursements'] = json_decode(json_encode($disbursements), true);

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
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->orderBy('hei_prov_code')->get()->toArray();
            }
            else {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $heiregion)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->orderBy('hei_prov_code')->get()->toArray();
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
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', 'like', '%15%')->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->orderBy('hei_prov_code')->get()->toArray();
            } else {
                $heiprovince = DB::table('tbl_heis')->select('hei_psg_region', 'hei_prov_name', 'hei_prov_code')->where('hei_psg_region', $heiregion)->groupBy('hei_prov_name', 'hei_psg_region', 'hei_prov_code')->orderBy('hei_prov_code')->get()->toArray();
            }

            if ($heiprov < count($heiprovince)) {
                $heiprovcode = $heiprovince[$heiprov]->hei_prov_code;
                $heis = DB::table('tbl_heis')->select('hei_region_nir')->select('hei_uii')->addSelect('hei_prov_name')->addSelect('hei_shortname')->addSelect('hei_it')->addSelect('hei_ct')->where('hei_it', 'like', '%' . $hei_it . '%')->where('hei_prov_code', '=', $heiprovcode)
                    ->where('hei_focal', '!=', 'NO UNIFAST')
                    ->where('hei_focal', '!=', 'HEI CLOSED')
                    ->where('hei_focal', '!=', 'INACTIVE')
                    ->where('hei_focal', '!=', 'NO GRANTEES')
                    ->where('hei_focal', '!=', 'No TES grantees')
                    ->where('hei_focal', '!=', 'NOT OPERATIONAL')
                    ->where('hei_focal', '!=', '')
                    ->whereNotNull('hei_focal')
                    ->orderBy('hei_shortname')->get();
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
                "hei_psg_region": "1",
                "hei_region_nir": "1 - Ilocos Region"
            },
            {
                "hei_psg_region": "2",
                "hei_region_nir": "2 - Cagayan Valley"
            },
            {
                "hei_psg_region": "3",
                "hei_region_nir": "3 - Central Luzon"
            },
            {
                "hei_psg_region": "4",
                "hei_region_nir": "4 - CALABARZON"
            },
            {
                "hei_psg_region": "5",
                "hei_region_nir": "5 - Bicol Region"
            },
            {
                "hei_psg_region": "6",
                "hei_region_nir": "6 - Western Visayas"
            },
            {
                "hei_psg_region": "7",
                "hei_region_nir": "7 - Central Visayas"
            },
            {
                "hei_psg_region": "8",
                "hei_region_nir": "8 - Eastern Visayas"
            },
            {
                "hei_psg_region": "9",
                "hei_region_nir": "9 - Zamboanga Peninsula"
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
