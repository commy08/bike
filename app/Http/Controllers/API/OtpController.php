<?php

namespace App\Http\Controllers\API;

use App\Model\Otps;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OtpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $del = new OtpController();
        $num=rand( 1000 , 9999 );
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charsLength = strlen($characters) -1;
        $ref = "";
        for($i=0; $i<4; $i++){
            $randNum = mt_rand(0, $charsLength);
            $ref .= $characters[$randNum];
        }
        $data = [
            'ref' => $ref,
            'num' => $num
        ];

        $otp = new Otps();
        $otp->fill($data);
        $otp->save();
       /* $id = $otp->id;
        $otp = $del->destroy($id);*/
        return $otp;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $otp = Otps::find($id);
        $otp->delete();
        return $otp;
    }
}
