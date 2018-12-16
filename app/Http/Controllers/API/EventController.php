<?php

namespace App\Http\Controllers\API;

use DB;
use App\Model\Users;
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
        $user = new UsersController();

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
        ];
    }

    public function SelectByProvince(){
        $province = urldecode($_GET['province']);
        $event = DB::table('pics')
        ->groupBy('event_id')
        ->join('events' , 'pics.event_id' , '=' , 'events.id')
        ->where('provinces',$province)->where('status','true')
        ->get();
        return $event;
    }

    public function test(){
        $pic = DB::table('pics')
        ->groupBy('event_id')
        ->join('events' , 'pics.event_id' , '=' , 'events.id')
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

        $data = $request->all();
        // $data = json_decode(file_get_contents('php://input'),true);
        return  $data;
        
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        
        $type = Users::where('line_id',$userId)->first();
        $data['form']['user_id'] = $type->id;
        $EventForm = $data['form'];
        $DivisionsForm = $data['division'];
          
        if ($type->type == 'org') {
            $event->fill($EventForm);
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
            // $data['form']['imgs']['event_id'] = $event_id;
            // $PicForm = $data['img'];

            $pic->insert($PicForm);
            // $pic->save();

            //count form before save***
            $divisionForm = [];
            foreach ($data['form']['division'] as $value) {
                $tmp = [
                    'DivisionName' => $value['DivisionName'],
                    'ageMin' => $value['ageMin'],
                    'ageMax' => $value['ageMax'],
                    'sex' => $value['sex'],
                    'cost' => $value['cost'],
                    'event_id' => $value['event_id']
                ];
                array_push($divisionForm,$tmp);
            }
            /*$data['division']['event_id'] = $event_id;
            $DivisionsForm = $data['division'];*/

            $divisions->insert($DivisionsForm);
            // $divisions->save();

            $output = array(
                'status' => 200,
                'msg' => "Create Event Complete" ,
            );
 
            return $output;
        }

        $output = array(
            'status' => 401,
            'msg' => "No Permission" ,
        );

        return $output;
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Events::find($id);
        $pic = Pics::where('event_id',$id)->get();
        $output = array_merge(['event' => $event],['pic' => $pic]);
        return $output;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $user = new UserController();
        $event = new Events();

        $data = $request->all();
        $token = $data['token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();


        // $event = Events::find($id);
        // $event->fill($request->all());
        // $event->save();
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
            Events::where('id',$updateID)->update(['status' => 'true']);

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

    //แสดง event ของ org แต่ละคน หน้ากิจกรรมของ org
    public function showEventOrg(){
        $user = new UsersController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'org') {
            return [
                'event' => DB::table('events')
                ->join('divisions','divisions.events_id','=','events.id')
                ->join('invoices','invoices.division_id','=','divisions.id')
                ->get()
            ];
        }
    }
}
