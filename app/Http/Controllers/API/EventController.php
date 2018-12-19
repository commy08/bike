<?php

namespace App\Http\Controllers\API;

use DB;
use App\Model\Users;
use App\Model\Payments;
use App\Model\Events;
use App\Model\Pics;
use App\Model\Provinces;
use App\Model\Divisions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $event = DB::table('pics')
        ->groupBy('pics.event_id')
        ->join('events','pics.event_id','=','events.event_id')
        ->where('status','true')
        ->get();
        return $event;
        /*$user = new UsersController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin') {
            return [
               'event' => Events::get()
            ];
        }
        if ($type->type == 'org') {
            return [
                'event' =>  Events::where('user_id',$type->id)->get()
            ];
        }
        return [
            'event' =>  Events::where('status','true')->get()
        ];*/
    }

    public function SelectByProvince(){
        $province = urldecode($_GET['province']);
        $event = DB::table('pics')
        ->groupBy('pics.event_id')
        ->join('events','pics.event_id','=','events.event_id')
        ->where('provinces',$province)->where('status','true')
        ->get();
        return $event;
    }

    public function test(){
        $pic = DB::table('pics')
        ->groupBy('event_id')
        ->join('events','pics.event_id','=','events.event_id')
        ->where('provinces',$province)->where('status','true')
        ->get();
        return $pic;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){ 
        $user = new UsersController();
        $event = new Events();
        $pic = new Pics();
        $divisions = new Divisions();

        $data = json_decode(file_get_contents('php://input'),true);
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        
        $type = Users::where('line_id',$userId)->first();
        $data['form']['user_id'] = $type->user_id;
        $DivisionsForm = $data['form']['divisions'];


        $eventData = [
            'EventName' => $data['form']['EventName'],
            'user_id' => $type->user_id,
            'detail' => $data['form']['detail'],
            'location' => $data['form']['location'],
            'provinces' => $data['form']['address']['provinces'],
            'amphurs' => $data['form']['address']['amphurs'],
            'dateClose' => $data['form']['dateClose'],
            'dateDeadline' => $data['form']['dateDeadline'],
            'dateRace' => $data['form']['dateRace'],
            'type' => $data['form']['type'],
            'rule' => $data['form']['rule'],
            'reward' => $data['form']['reward'],
            'youtube' => $data['form']['youtube'],
        ];

        if ($type->type == 'org') {
            if ($type->status == 'true') {
                $event->fill($eventData);
                $event->save();
                $event_id = $event->id;

                //count form before save***
                $PicForm = [];
                foreach ($data['form']['imgs'] as $value) {
                    $tmp = [
                        'PicData' => $value['base64'],
                        'event_id' => $event_id
                    ];
                    array_push($PicForm,$tmp);
                }
                $pic->insert($PicForm);

                //count form before save***
                $divisionForm = [];
                foreach ($data['form']['divisions'] as $value) {
                    $tmp = [
                        
                        'DivisionName' => $value['DivisionName'],
                        'event_id' => $event_id,
                        'ageMin' => $value['ageMin'],
                        'ageMax' => $value['ageMax'],
                        'sex' => $value['sex'],
                        'cost' => $value['cost']
                    ];
                    array_push($divisionForm,$tmp);
                }
                $divisions->insert($divisionForm);

                $output = array(
                    'status' => 200,
                    'msg' => "Create Event Complete" ,
                );
                return $output;
                }else {
                    $output = array(
                    'status' => 401,
                    'msg' => "No Permission" ,
                );
                    return $output;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $event = Events::where('event_id',$id)->first();
        $status = $event->status;
        // return $status;
        if ($status == 'true') {
            $event = Events::where('event_id',$id)->first();
            $pic = Pics::where('event_id',$id)->get();
            $payment = Payments::where('user_id',$event->user_id)->get();
            $output = array_merge(['event' => $event],['pic' => $pic],['payment' => $payment]);
            return $output;
        }else {
            $output = array(
                'status' => 400,
                'msg' => 'Error No Permission',
            );
            
            return $output;
        }

        
    }

    public function updateStatusEvent(Request $request){
        $user = new UsersController();

        $data = json_decode(file_get_contents('php://input'),true);
        $getUser = $user->getProfile($data['access_token']);
        $updateID = $data['event_id'];
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin'){
            $user_id = Events::where('event_id',$updateID)->first();
            $event = Events::where('event_id',$updateID)->first();
            $user_id = $user_id->user_id;
            $user_id = Users::where('user_id',$user_id)->first();
            $name = $event->EventName;
            $token = $user_id->line_token;
            Events::where('event_id',$updateID)->update(['status' => 'true']);
            $this->sendMsgUpdateUser($token,$name);
            $output = array(
                'status' => 200,
                'msg' => 'Update Status User Complete',
            );
            return $output;
        }else {
            $output = array(
                'status' => 400,
                'msg' => 'Error No Permission',
            );
    
            return $output;
        }
    }

    public function sendMsgUpdateUser($token,$event){
        $msg = "กิจกรรม ".$event." ของท่านของท่านได้รับการอนุมัติแล้ว";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://notify-api.line.me/api/notify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "message=".urlencode($msg),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".$token,
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    //แสดง event ของ org แต่ละคน หน้ากิจกรรมของ org
    public function showEventOrg(){
        $user = new UsersController();

        $token = urldecode($_GET['token']);
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'org') {
            $eventCount = DB::table('events')
            ->where('user_id',$type->user_id)
            ->count();

            // $invoiceCount = DB::

            $event = DB::table('pics')
            ->groupBy('pics.event_id')
            ->join('events','pics.event_id','=','events.event_id')
            ->where('user_id',$type->user_id)
            ->get();

            $output = array_merge(['NumberOfEvent' => $eventCount],['Event' => $event]);

            // return array_merge(['NumberOfEvent' =>  $eventCount],['Event' => $event]);
            return $output;
        }
    }

    public function getEventType(){
        $type = urldecode($_GET['type']);
        // return $type;
        // die();
        if ($type == 'mountain') {
            $event = DB::table('pics')
            ->groupBy('pics.event_id')
            ->join('events' , 'pics.event_id' , '=' , 'events.event_id')
            ->where('status','true')
            ->where('type','จักรยานภูเขา')
            ->get();
            return $event;
        }
        elseif ($type == 'road') {
            $event = DB::table('pics')
            ->groupBy('pics.event_id')
            ->join('events' , 'pics.event_id' , '=' , 'events.event_id')
            ->where('status','true')
            ->where('type','จักรยานทางเรียบ')
            ->get();
            return $event;
        }else{
            $output = array(
                'status' => 404,
                'msg' => "undifined" ,
            );
            return $output;
        }

        
        

        
    }

    public function showEventAdmin(){
        $user = new UsersController();
        $token = urldecode($_GET['token']);
        // dd($token);
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin') {
            $event = DB::table('pics')
            ->groupBy('pics.event_id')
            ->join('events','pics.event_id','=','events.event_id')
            ->where('status','false')
            ->get();

            $eventCount = DB::table('events')
            ->where('status','false')
            ->count();

            $output = array_merge(['NumberOfEvent' => $eventCount],['Event' => $event]);
            return $output;
        }else {
            $output = array(
                'status' => 400,
                'msg' => 'Error No Permission',
            );

            return $output;
        }
    }

    public function showEvent(){
        $user = new UsersController();
        $token = urldecode($_GET['token']);
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'admin') {
            $event = DB::table('pics')
            ->groupBy('pics.event_id')
            ->join('events','pics.event_id','=','events.event_id')
            ->where('status','true')
            ->get();

            $eventCount = DB::table('events')
            ->where('status','true')
            ->count();

            $output = array_merge(['NumberOfEvent' => $eventCount],['Event' => $event]);
            return $output;
        }else {
            $output = array(
                'status' => 400,
                'msg' => 'Error No Permission',
            );

            return $output;
        }
    }
}
