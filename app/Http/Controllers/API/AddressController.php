<?php

namespace App\Http\Controllers\API;

use App\Model\Amphurs;
use App\Model\Provinces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        header('Access-Control-Allow-Origin: *');
        $province = Provinces::get();
        $amphur = Amphurs::get();
        $output = array_merge(['provinces' => $province],['amphurs' => $amphur]);
        return $output;
    }

    public function getCity()
    {
        
        $province = Provinces::get();
        return $province;
       
    }

    public function getAmp($id)
    { 
        
        $amp = Amphurs::where('province_id',$id)->get();
        return $amp; 
        
    }
}
