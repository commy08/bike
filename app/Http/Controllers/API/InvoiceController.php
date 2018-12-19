<?php

namespace App\Http\Controllers\API;

use DB;
use App\Model\Invoices;
use App\Model\Users;
use App\Model\Divisions;
use App\Model\Events;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;

class InvoiceController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data = $request->all();
        $invoice = new Invoices();
        $invoice->fill($data);
        $invoice->save();

        $output = array(
            'status' => 200,
            'msg' => "Create Invoice Complete" ,
        );

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return Invoices::get($id);
    }

    public function history(){
        $user = new UsersController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type = 'user') {
            return [
                'invoices' => DB::table('invoices')
                ->join('divisions','divisions.division_id','=','invoices.division_id')
                ->join('events','events.event_id','=','divisions.event_id')
                ->where('user_id',$type->user_id)
                ->get()
            ];
        }
    }
    
     public function ShowAllInvoiceUser(){
        $user = new UsersController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'user') {
            $invoicecount = DB::table('invoices')
            ->join('divisions','divisions.division_id', '=', 'invoices.division_id')
            ->join('events','events.event_id','=','divisions.event_id')
            ->where('invoices.user_id',$type->user_id)
            ->count();

            $invoice = DB::table('invoices')
            ->join('users','users.user_id','=','invoices.user_id')
            ->join('divisions','divisions.division_id', '=', 'invoices.division_id')
            ->join('events','events.event_id','=','divisions.event_id')
            ->where('invoices.user_id',$type->user_id)
            ->get();

            $output = array_merge(['NumberOfInvoice' => $invoicecount],['Invoices' => $invoice]);
            return $output;
        }
    }

    public function ShowAllInvoiceOrg(){
        $user = new UsersController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == 'org') {
            $invoicecount = DB::table('invoices')
            ->join('divisions','divisions.division_id', '=', 'invoices.division_id')
            ->join('events','events.event_id','=','divisions.event_id')
            ->where('invoices.status','=','false')
            ->where('events.user_id',$type->user_id)
            ->count();

            $invoice = DB::table('invoices')
            ->join('users','users.user_id','=','invoices.user_id')
            ->join('divisions','divisions.division_id', '=', 'invoices.division_id')
            ->join('events','events.event_id','=','divisions.event_id')
            ->where('invoices.status','=','false')
            ->where('events.user_id',$type->user_id)
            ->get();

            $output = array_merge(['NumberOfInvoice' => $invoicecount],['Invoices' => $invoice]);
            return $output;
        }
    }

    public function UpdateInvoice(Request $request){
        $user = new UsersController();
        $user_id = new Users();
        
        $data = json_decode(file_get_contents('php://input'),true);
        $invoice_id = $data['invoice_id'];
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        $id = Invoices::where('invoice_id',$invoice_id)->first();
        $id = $id->user_id;
        $line_token = $user_id->where('user_id',$id)->first();
        $line_token = $line_token->line_token;

        if ($type->type == 'org') {
                $invoice = Invoices::where('invoice_id',$invoice_id)->update(['status' => 'true']);
                $this->sendMsgUpdateUser($line_token,$invoice_id);

                $output = array(
                    'status' => 200,
                    'msg' => 'Update Status Invoice id : '.$invoice_id.' Complete',
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

    public function sendMsgUpdateUser($token,$id){
        $msg = "ใบ Invoice หมายเลข : ".$id." ของท่านได้รับการอนุมัติแล้ว";
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

    public function UpdateInvoicePic(Request $request){
        $user = new UsersController();
        
        $data = json_decode(file_get_contents('php://input'),true);
        $invoice_id = $data['invoice_id'];
        $invoice_pic = $data['pic'];
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();

        if ($type->type == 'user') {
                Invoices::where('invoice_id',$invoice_id)->update(['pic' => $invoice_pic]);

                $output = array(
                    'status' => 200,
                    'msg' => 'Update Transfer Slip Invoice id : '.$invoice_id.' Complete',
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



        /*$invoice = Invoices::find($id);
        $invoice->fill($request->all());
        $invoice->save();*/