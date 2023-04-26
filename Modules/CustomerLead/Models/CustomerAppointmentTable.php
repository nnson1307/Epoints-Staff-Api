<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAppointmentTable extends Model
{
    protected $table = "customer_appointments";
    protected $primaryKey = "customer_appointment_id";

    const NOT_DELETED = 0;

    public  function getNumberOfAppointment($id){
        $mSelect = $this
            -> select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.customer_id",
                "{$this->table}.customer_appointment_type"
            )
            ->where("{$this->table}.customer_id",$id);
        return $mSelect->get();

    }
}