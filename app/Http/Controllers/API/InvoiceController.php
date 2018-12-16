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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $invoice = Invoices::find($id);
        $invoice->fill($request->all());
        $invoice->save();
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
                ->join('divisions','divisions.id','=','invoices.division_id')
                ->join('events','events.id','=','divisions.events_id')
                ->where('user_id',$type->id)
                ->get()
            ];
        }
    }
}
