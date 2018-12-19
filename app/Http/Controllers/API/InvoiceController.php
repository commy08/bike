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
            ->where('events.user_id',$type->user_id)
            ->count();
            // die("invoicecount");

            $invoice = DB::table('invoices')
            ->join('users','users.user_id','=','invoices.user_id')
            ->join('divisions','divisions.division_id', '=', 'invoices.division_id')
            ->join('events','events.event_id','=','divisions.event_id')
            ->where('events.user_id',$type->user_id)
            ->get();

            $output = array_merge(['NumberOfInvoice' => $invoicecount],['Invoices' => $invoice]);
            return $output;
        }
    }

    public function UpdateInvoice(Request $request){
        $user = new UsersController();
        
        $data = json_decode(file_get_contents('php://input'),true);
        // return $data;
        // die();
        $invoice_id = $data['invoice_id'];
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();

        if ($type->type == 'org') {
                Invoices::where('invoice_id',$invoice_id)->update(['status' => 'true']);

                $output = array(
                    'status' => 200,
                    'msg' => 'Update Status Invoice id : '+$invoice_id +'Complete',
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

    public function UpdateInvoicePic(Request $request){
        $user = new UsersController();
        
        $data = json_decode(file_get_contents('php://input'),true);
        // return $data;
        // die();
        $invoice_id = $data['invoice_id'];
        $invoice_pic = $data['invoice_pic'];
        $token = $data['access_token'];
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();

        if ($type->type == 'user') {
                Invoices::where('invoice_id',$invoice_id)->update(['pic' => $invoice_pic]);

                $output = array(
                    'status' => 200,
                    'msg' => 'Update Transfer Slip Invoice id : '+$invoice_id +'Complete',
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