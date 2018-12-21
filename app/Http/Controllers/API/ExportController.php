<?php

namespace App\Http\Controllers\API;

use DB;
use Illuminate\Http\Request;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
// use App\Exports;

class ExportController extends Controller
{
    public function ExportToExcel(){
        $id = $_GET['id'];

        $eventName = DB::table('events')
        ->where('events.event_id',$id)
        ->first();
        $eventName = $eventName->EventName;

        $test = new EventsExport();
        $test->id = $id;
        $tmp = $test->collection();

        return Excel::download($test, 'ข้อมูลผู้สมัครเข้าร่วมกิจกรรม '.$eventName.'.xlsx');
    }

    public function export($test) 
    {
        return Excel::download($test, 'data.xlsx');
    }
}
