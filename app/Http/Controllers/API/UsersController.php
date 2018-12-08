<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *as
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = array(
            'url' => 'https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id=1602409871&redirect_uri=http://192.168.1.107:8080/callback&state=peerapat123456789',
        );
// header('Access-Control-Allow-Origin: *');
        die(json_encode($url));
    }

    public function callback()
    {

        $parameter = array(
            'grant_type' => 'authorization_code',
            'code' => trim($_GET['code']),
            'redirect_uri' => 'http://192.168.1.107:8080/callback',
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
        // header('Access-Control-Allow-Origin: *');
                die(json_encode($output));
            }

            #ถ้าเกิดเข้าสู้ระบบสำเร็จ
            $output = array(
                'status' => 200,
                'msg' => 'Success',
                'access_token' => $accessToken
            );
    // header('Access-Control-Allow-Origin: *');
            die(json_encode($output));

        }else{
            $output = array(
                'status' => 403,
                'msg' => 'error token'
            );
    // header('Access-Control-Allow-Origin: *');
            die(json_encode($output));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    public function registerUser(Request $request){

        $tmp = json_decode(file_get_contents('php://input'),true);
        $getUser = $this->getProfile($tmp['access_token']);

        $id =  $getUser->userId;

        $form =  $tmp['form'];


        $db = User::where('line_id',$id)->update(
            [
                'firstname' => $form['firstname'],
                'lastname' =>$form['lastname'],
                'tel' =>$form['tel'],
                'sex' => $form['sex'],
                'address' => $form['address'],
                'provinces' => $form['provinces'],
                'amphurs' => $form['amphurs'],
                'birthday' => $form['date'],
                'picID' => $form['picID'],
                'type' => 'user'
            ]
        );


// header('Access-Control-Allow-Origin: *');
        die(json_encode(['status'=>true]));
    }
    private function getProfileID($access_token){
        if($access_token){
            $response = $this->curl('https://api.line.me/v2/profile',array(),'GET',array('Authorization: Bearer '.$access_token));
            return $response;
        }else{
            return null;
        }

    }
    public function registerOrg(Request $request){
// header('Access-Control-Allow-Origin: *');
        // $tmp = $request->all();

        // die();
        $tmp = json_decode(file_get_contents('php://input'),true);
        $getUser = $this->getProfile($tmp['access_token']);

        $id =  $getUser->userId;
        // print_r($tmp['form']['picID']);
        // die($id);
        $form =  $tmp['form'];


        $db = User::where('line_id',$id)->update(
            [
                'firstname' => $form['firstname'],
                'lastname' =>$form['lastname'],
                'tel' =>$form['tel'],
                'sex' => $form['sex'],
                'address' => $form['address'],
                'provinces' => $form['provinces'],
                'amphurs' => $form['amphurs'],
                'birthday' => $form['date'],
                'tradeNum' => $form['tradeNum'],
                'OrgName' => $form['OrgName'],
                'picID' => $form['picID'],
                'picORG' => $form['picORG'],
                'type' => 'org'
            ]
        );
        return TRUE;

// header('Access-Control-Allow-Origin: *');
        die(json_encode(['status'=>true]));
    }


    public function showUser(Request $request,$system=false)
    {

        $tmp = json_decode(file_get_contents('php://input'),true);

        $getUser = $this->getProfileID( $tmp['access_token']);

        $uu = json_decode($getUser,true);
        $line_id = $uu['userId'];

        $db = DB::table('users')->where('line_id',$line_id);
        $count = $db->count();
        $users = $db->get();
        if ($count == 0){

            $user = new User();
            $user->line_id = $uu['userId'];
            $user->firstname = $uu['displayName'];
            $user->line_pic = $uu['pictureUrl'];
            $user->save();

        }

// header('Access-Control-Allow-Origin: *');
        die(json_encode($users[0]));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user =  User::find($id);
        $user->delete();
    }
}
