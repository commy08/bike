<?php

namespace App\Http\Controllers\API;

use DB;
use App\Model\Divisions;
use App\Model\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Divisions::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $division = new Divisions();
        $division->fill($request->all());
        $division->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Divisions::find($id);
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
        $division = Divisions::find($id);
        $division->fill($request->all());
        $division->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Divisions::find($id);
        $division->delete();
    }

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
        $year = DB::table('users')->where('id',$type->id)->selectRaw('substr(birthday,1,4) as dates')->pluck('dates')->unique()->first();
        $year = (int)$year;
        if ($type->type = 'user') {
            $age = $currentyear-$year;
            return [
                'divisions' => Divisions::where('ageMax','>=',$age)
                ->where('ageMin','<=',$age)
                ->where('events_id',$idEvent)
                ->get()
            ];
        }
    }
}
