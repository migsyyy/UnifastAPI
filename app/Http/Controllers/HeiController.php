<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hei;


class HeiController extends Controller
{
    public function getHeiTypes()
    {
        $heimodel = new Hei();
        echo $heimodel->getHeiTypes();
    }
    public function getHeiRegions()
    {
        // $heimodel = new Hei();
        // echo $heimodel->getHeiRegions();

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
    public function getHeiProvinces($heiregion)
    {
        $heimodel = new Hei();
        echo $heimodel->getHeiProvinces($heiregion);
    }
    public function showhei($heiregion = '', $heiprov = '', $heitype = '') //list heis per region
    {
        $heimodel = new Hei();
        echo $heimodel->showHei( $heiregion, $heiprov, $heitype);
    }
    public function searchhei($heiname) //search hei by name
    {
        $heimodel = new Hei();
        echo $heimodel->searchHeiName($heiname);
    }
    public function getCourses($heiuii) //list courses by heiuii
    {
        $heimodel = new Hei();
        echo $heimodel->getCourses($heiuii);
    }

    public function getHeiInfo($heiuii)
    {
        $heimodel = new Hei();
        echo $heimodel->getHeiInfo($heiuii);
    }
}
