<?php

namespace App\Http\Controllers\API;


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
    public function index()
    {

      //  include app_path().'\\Http\\Controllers\\UserController.php';
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
            // $event['event'] = Events::where('user_id',$type->id)->get();
            // $output['event'] = $event;
            return [
                'event' =>  Events::where('user_id',$type->id)->get()
            ];
        }
        return [
            'event' =>  Events::where('status','true')->get()
        ];
    }

    public function select(){
        $province = urldecode($_GET['province']);
         return Events::where('provinces',$province)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $user = new UsersController();
        $event = new Events();
        $pic = new Pics();
        $divisions = new Divisions();

        $data = $request->all();
        // return  $data;
        
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        
        $type = Users::where('line_id',$userId)->first();
        $data['form']['user_id'] = $type->id;
        $EventForm = $data['form'];
        $DivisionsForm = $data['division'];
        
        // dd($form);
        // die(json_encode($form));
        if ($type->type == 'org') {
            $event->fill($EventForm);
            $event->save();
            $event_id = $event->id;

            //count form before save***
            $data['img']['event_id'] = $event_id;
            $PicForm = $data['img'];

            $pic->fill($PicForm);
            $pic->save();

            //count form before save***
            $data['division']['event_id'] = $event_id;
            $DivisionsForm = $data['division'];

            $divisions->fill($DivisionsForm);
            $divisions->save();

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
        return Events::find($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Events::find($id);
        $event->delete();
    }
}
