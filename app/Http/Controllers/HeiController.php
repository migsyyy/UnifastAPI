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
        $heimodel = new Hei();
        echo $heimodel->getHeiRegions();
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
