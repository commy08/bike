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
    public function index()
    {
        $user = new UserController();

        $token = $_GET['token'];
        $token = str_replace(' ','+',$token);
        $getUser = $user->getProfile($token);
        $userId = $getUser->userId;
        $type = Users::where('line_id',$userId)->first();
        if ($type->type == "racer") {
            return [
                'Invoices' => Invoices::where('user_id',$type->id)->get()
             ];
        }if ($type->type == 'org') {
            return [
                'Invoices' => DB::table('invoices')
                                ->join('users','invoices.user_id','=','users.id')
                                ->join('divisions','invoices.user_id','=','divisions.id')
                                ->join('events','divisions.events_id','=','events.id')
                                ->select('invoices.id','events.name','divisions.name','divisions.sex','divisions.cost','users.firstname','users.lastname','users.sex')
                                ->get()
             ];
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    public function show($id)
    {
        return Invoices::get($id);
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
        $invoice = Invoices::find($id);
        $invoice->fill($request->all());
        $invoice->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoices::find($id);
        $invoice->delete();
    }
}
