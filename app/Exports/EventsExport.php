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

        return Events::select('invoices.invoice_id','users.firstname','users.lastname','users.birthday','users.tel','divisions.DivisionName','divisions.ageMin','divisions.ageMax','divisions.cost')
        ->join('divisions','divisions.event_id','=','events.event_id')
        ->join('invoices','invoices.division_id','=','divisions.division_id')
        ->join('users','users.user_id','=','invoices.user_id')
        ->where('events.event_id',$tmp)
        ->get();
    }
}
