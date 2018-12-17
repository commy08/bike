<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Model\Users;
use App\Model\Payments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *as
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $url = array(
            'url' => 'https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id=1602409871&redirect_uri=http://192.168.1.105:8080/callback&state=peerapat123456789',
        );

        return $url;
    }

    public function callback(){
        $parameter = array(
            'grant_type' => 'authorization_code',
            'code' => trim($_GET['code']),
            'redirect_uri' => 'http://192.168.1.105:8080/callback', //ip frontend
            'client_id' => '1602409871',
            'client_secret' => '37a7d9312db424eda44f68689373dd9e'
        );
        $accessToken = $this->getAccessToken($parameter);
        if($accessToken){
            $getUser = $this->getProfile($accessToken);
            if(!$getUser) {
                $output = array(
                    'status' => 401,
                    'msg' => 'error login'
                );
        
                return $output;
            }

            #ถ้าเกิดเข้าสู้ระบบสำเร็จ
            $output = array(
                'status' => 200,
                'msg' => 'Success',
                'access_token' => $accessToken
            );
    
            return $output;

        }else{
            $output = array(
                'status' => 403,
                'msg' => 'error token'
            );
    
            return $output;
        }
    }

    public function getAccessToken($parameter=array()){
        $response = json_decode($this->curl('https://api.line.me/v2/oauth/accessToken',$parameter,'POST'));
        return isset($response->access_token) ? $response->access_token: FALSE;
    }

    public function getProfile($access_token=''){
        if($access_token == '') return FALSE;
        $response = json_decode($this->curl('https://api.line.me/v2/profile',array(),'GET',array('Authorization: Bearer '.$access_token)));
        return isset($response->userId) ? $response: FALSE;
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

    public function getProfileID($access_token){
        if($access_token){
            $response = $this->curl('https://api.line.me/v2/profile',array(),'GET',array('Authorization: Bearer '.$access_token));
            return $response;
        }else{
            return null;
        }
    }

    public function registerUser(Request $request){

        $tmp = json_decode(file_get_contents('php://input'),true);
        $getUser = $this->getProfile($tmp['access_token']);
        $id = $getUser->userId;
        $data = $tmp['form'];

        $db = Users::where('line_id',$id)->update(
            [
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'tel' =>$data['tel'],
                'sex' => $data['sex'],
                'address' => $data['location'],
                'provinces' => $data['address']['provinces'],
                'amphurs' => $data['address']['amphurs'],
                'birthday' => $data['date'],
                'picID' => $data['picID'],
                'type' => 'user'
            ]
        );
        die(json_encode(['status'=>true]));
    }

    public function registerOrg(Request $request){

        $tmp = json_decode(file_get_contents('php://input'),true);
        $getUser = $this->getProfile($tmp['access_token']);

        $id =  $getUser->userId;
        $data =  $tmp['form'];


        $db = Users::where('line_id',$id)->update(
            [
                'firstname' => $data['firstname'],
                'lastname' =>$data['lastname'],
                'tel' =>$data['tel'],
                'address' => $data['location'],
                'provinces' => $data['address']['provinces'],
                'amphurs' => $data['address']['amphurs'],
                'birthday' => $data['date'],
                'tradeNum' => $data['tradeNum'],
                'OrgName' => $data['OrgName'],
                'picID' => $data['picID'],
                'picORG' => $data['picORG'],
                'type' => 'org'
            ]
        );
        $uid = Users::where('line_id',$id)->first();
        $uid = $uid->id;

        $payment = new Payments();
        $bankForm = [];
        $count = count($data['banks']['accountNum']);
        for ($i=0; $i < $count; $i++) { 
            $tmp = [
                'user_id' => $uid,
                'BankName' => $data['banks']['bankName'][$i],
                'accountName' => $data['banks']['accountName'][$i], 
                'accountNum' => $data['banks']['accountNum'][$i]
            ];
            array_push($bankForm,$tmp);
        }
        $payment->insert($bankForm);
        die(json_encode(['status'=>true]));
    }


    public function showUser(Request $request,$system=false){
        $data = json_decode(file_get_contents('php://input'),true);
        $getUser = $this->getProfileID($data['access_token']);

        $uu = json_decode($getUser,true);
        $line_id = $uu['userId'];

        $db = DB::table('users')->where('line_id',$line_id);
        $count = $db->count();
        $users = $db->get();
        if ($count == 0){

            $user = new Users();
            $user->line_id = $uu['userId'];
            $user->firstname = $uu['displayName'];
            $user->line_pic = $uu['pictureUrl'];
            $user->save();
        }
        die(json_encode($users[0]));

    }

    public function show(){
        $user = new UsersController();
        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        return  Users::where('line_id',$userId)->first();
    }

    public function checkUser(){
        $user = new UsersController();
        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin') {
            return Users::get();
        }else {
            $output = array(
                'status' => 406,
                'msg' => 'No Permission',
            );
    
            return $output;
        }
    }

    public function updateStatus(Request $request){
        $user = new UsersController();

        $data = json_decode(file_get_contents('php://input'),true);
        // return $data;
        $getUser = $user->getProfile($data['access_token']);
        $updateID = $data['id'];
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin'){
            Users::where('id',$updateID)->update(['status' => 'true']);

            $output = array(
                'status' => 200,
                'msg' => 'Update Status User Complete',
            );
    
            return $output;
        }else {
            $output = array(
                'status' => 400,
                'msg' => 'Update Status User Fail',
            );
    
            return $output;
        }
    }
}
