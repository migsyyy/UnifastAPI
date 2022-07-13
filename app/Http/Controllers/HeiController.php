<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hei;


class HeiController extends Controller
{
    public function showhei($heitype = '', $heiregion = '', $heiprov = '') //list heis per region
    {
        $heimodel = new Hei();
        if (!$heitype) {
            echo $heimodel->getHeiTypes();
        }
        echo $heimodel->showHei($heitype, $heiregion);
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
