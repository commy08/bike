<?php

namespace App\Http\Controllers\API;

use DB;
use App\Model\Divisions;
use App\Model\Users;
use App\Model\Invoices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    public function getDivision(){
        $user = new UsersController();

        $currentyear = date("Y");
        $currentyear = (int)$currentyear;
        $token = $_GET['token'];
        $idEvent = $_GET['id'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        $year = DB::table('users')->where('user_id',$type->user_id)->selectRaw('substr(birthday,1,4) as dates')->pluck('dates')->unique()->first();
        $year = (int)$year;
        $age = $currentyear-$year;
        if ($type->type = 'user') {
            return [
                'divisions' => Divisions::where('ageMax','>=',$age)
                ->where('ageMin','<=',$age)
                ->where('sex',$type->sex)
                ->where('event_id',$idEvent)
                ->get(),
                'CountDivision' => Divisions::where('ageMax','>=',$age)
                ->where('ageMin','<=',$age)
                ->where('sex',$type->sex)
                ->where('event_id',$idEvent)
                ->count()
            ];
        }
    }

    public function CreateInvoice(Request $request){
        $invoice = new Invoices();
        $user = new UsersController();
        /*$token = $_GET['token'];
        $idDiv = $_GET['division_id'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();*/
        $data = json_decode(file_get_contents('php://input'),true);
        $idDiv = $data['division_id'];
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        $invData = [
            'user_id' => $type->user_id,
            'division_id' => $idDiv
        ];
        if ($type->type == 'user') {
            $invoice->fill($invData)->save();

            $output = array(
                'status' => 200,
                'msg' => 'Create Invoice Complete',
            );
            return $output;
        }else{
            $output = array(
                'status' => 401,
                'msg' => "No Permission" ,
            );
                return $output;
        }
    }
}
