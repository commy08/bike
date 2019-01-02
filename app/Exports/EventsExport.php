<?php

namespace App\Exports;

use App\Model\Events;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Controller;

class EventsExport implements FromCollection
{
    public $id;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $tmp = $this->id;

        // $event = Events::select('invoices.invoice_id','users.firstname','users.lastname','users.sex','users.birthday','users.tel','divisions.DivisionName','divisions.ageMin','divisions.ageMax','divisions.cost','invoices.status')
        // ->join('divisions','divisions.event_id','=','events.event_id')
        // ->join('invoices','invoices.division_id','=','divisions.division_id')
        // ->join('users','users.user_id','=','invoices.user_id')
        // ->where('events.event_id',$tmp)
        // ->get();
    
        // $event_array[] = array('หมายเลขใบ Invoice','ชื่อ','นามสกุล', 'เพศ', 'วันเกิด', 'หมายเลขโทรศัพท์', 'ชื่อคลาส','อายุน้อยสุด','อายุมากสุด','ราคา','สถานะการชำระเงิน');

        // foreach($event as $event)
        //     {
        //         $event_array[] = array(
        //         'หมายเลขใบ Invoice'  => $event->invoice_id,
        //         'ชื่อ'   => $event->firstname,
        //         'นามสุกล'    => $event->lastname,
        //         'เพศ'  => $event->PostalCode,
        //         'วันเกิด'   => $event->Country,
        //         'หมายเลขโทรศัพท์' => $event->tel,
        //         'ชื่อคลาส' => $event->DivisionName,
        //         'อายุน้อยสุด' => $event->ageMin,
        //         'อายุมากสุด' => $event->ageMax,
        //         'ราคา' => $event->cost,
        //         'สถานะการชำระเงิน' => $event->status
        //         );
        //     }

        return Events::select('invoices.invoice_id','users.firstname','users.lastname','users.sex','users.birthday','users.tel','divisions.DivisionName','divisions.ageMin','divisions.ageMax','divisions.cost','invoices.status')
        ->join('divisions','divisions.event_id','=','events.event_id')
        ->join('invoices','invoices.division_id','=','divisions.division_id')
        ->join('users','users.user_id','=','invoices.user_id')
        ->where('events.event_id',$tmp)
        ->get();
    }
}
