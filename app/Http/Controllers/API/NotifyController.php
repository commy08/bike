<?php

namespace App\Http\Controllers\API;


use Redirect;
use App\Model\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // $url = array(
        //     'url' => 'https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id=1602409871&redirect_uri=http://192.168.1.103:8080/api/callbacknotify&state=peerapat123456789',
        // );
        $oauth_authorize = array(
            'response_type' => 'code',
            'client_id' => 'eLN5HgKKW9Ms14eZtKKpby',
            'scope' => 'notify',
            'state' => 'peerapat123456789',
            'response_mode' => 'form_post',
            'redirect_uri' => 'http://192.168.1.39:8080/api/callbacknotify?line_id=U03b830f026405a8987b7e62933b1493c',
        );
        $output = array(
            'status' => 200,
            'url' => 'https://notify-bot.line.me/oauth/authorize?'.http_build_query($oauth_authorize),
        );
        return $output;
    }

    public function callbackNotify(){
        //ip หน้าบ้าน
        if($_POST['code'] && $_GET['line_id']){
            $parameter = array(
                'grant_type' => 'authorization_code',
                'code' => trim($_POST['code']),
                'redirect_uri' => 'http://192.168.1.39:8080/api/callbacknotify?line_id='.$_GET['line_id'],
                'client_id' => 'eLN5HgKKW9Ms14eZtKKpby',
                'client_secret' => '43jqioufgsilyd8qDJTcWpSqDHyAl0E3m9adjPHkeKf'
            );
            $response = json_decode($this->curl('https://notify-bot.line.me/oauth/token',$parameter,'POST'));
            // dd($response);
            if($response->status === 200){
                Users::where('line_id',$_GET['line_id'])->update(['line_token' => $response->access_token]);
                //ip หน้าบ้าน
                die('<script>location="http://192.168.1.37:8080/";</script>');
            }else{
                //ip หน้าบ้าน
                die('<script>location="http://192.168.1.37:8080/";</script>');
            }				
			
        }else{
            //ip หน้าบ้าน
            die('<script>location="http://192.168.1.37:8080/";</script>');
		}
    }

    public function curl($url=null,$parameter=array(),$method='GET',$header=array()){
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method
        );
        if(!empty($parameter)) $options[CURLOPT_POSTFIELDS] = http_build_query($parameter);
        if(!empty($header)) $options[CURLOPT_HTTPHEADER] = $header;
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendMsgUpdateUser(){
        $msg = "ได้ชำระเงินเสร็จสมบูรณ์แล้ว\n";
        $msg = "------------------------\n";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://notify-api.line.me/api/notify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "message=".urlencode($msg),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$value->shops_tokennotify,
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
