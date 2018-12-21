<?php

namespace App\Http\Controllers\API;

use DB;
use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function ExportToExcel(){
        $id = $_GET['id'];
        $data = DB::table('events')
        ->join('divisions','divisions.event_id','=','events.event_id')
        ->join('invoices','invoices.division_id','=','divisions.division_id')
        ->join('users','users.user_id','=','invoices.user_id')
        ->where('events.event_id',$id)
        ->get()
        ->toArray();

        $eventName = DB::table('events')
        ->where('events.event_id',$id)
        ->first();
        $eventName = $eventName->EventName;

        $data_array[] = array('หมายเลข invoice','ชื่อ','นามสกุล','วันเกิด','อายุ','เบอร์โทร','รายการ','อายุน้อยสุด','อายุมากสุด','ค่าสมัคร');
        foreach ($data as $user) {
            $data_array[] = array(
                'หมายเลข invoice' => $user->invoice_id,
                'ชื่อ' => $user->firstname,
                'นามสกุล' => $user->lastname,
                'วันเกิด' => $user->birthday,
                'เบอร์โทร' => $user->tel,
                'รายการ' => $user->DivisionName,
                'อายุน้อยสุด' => $user->ageMin,
                'อายุมากสุด' => $user->ageMax,
                'ค่าสมัคร' => $user->cost
            );
        }

        // Excel::create('ข้อมูลผู้สมัครเข้าร่วมกิจกรรม '.$eventName, function($excel);
            Excel::create('Customer Data', function($excel) use ($data_array){
                    $excel->setTitle('Customer Data');
                $excel->sheet('Customer Data', function($sheet) use ($data_array){
                    $sheet->fromArray($data_array, null, 'A1', false, false);
                });
            })->download('xlsx');

            $output = array(
                'status' => 200,
                'msg' => 'Export complete',
            );
            
            return $output;
    
        
    }
}
