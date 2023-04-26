<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SFShiftsTable extends Model
{
    protected $table = "sf_shifts";
    protected $primaryKey = "shift_id";
    protected $fillable = [
        "shift_id",
        "shift_name",
        "shift_type",
        "start_work_time",
        "end_work_time",
        "start_lunch_break",
        "end_lunch_break",
        "start_timekeeping_on",
        "end_timekeeping_on",
        "start_timekeeping_out",
        "end_timekeeping_out",
        "timekeeping_coefficient",
        "min_time_work",
        "time_work",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Get danh sách ca làm việc
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
       
        $oSelect = $this
            ->select(
                $this->table.'.shift_name',
                $this->table.'.shift_id',
                $this->table.'.start_work_time',
                $this->table.'.end_work_time',
                $this->table.'.start_timekeeping_on',
                $this->table.'.end_timekeeping_on',
                $this->table.'.start_timekeeping_out',
                $this->table.'.end_timekeeping_out',
                $this->table.'.min_time_work',
                $this->table.'.time_work',
                'sf_time_working_staffs.working_day',
                'sf_time_working_staffs.time_working_staff_id',
            
            )->leftJoin("sf_time_working_staffs", "sf_time_working_staffs.shift_id", "=", "{$this->table}.shift_id");
            
            if ($data['working_day_start'] && $data['working_day_end']) {
               
                $start = Carbon::createFromFormat('Y-m-d',$data['working_day_start'])->format('Y-m-d 00:00:00');
                $end = Carbon::createFromFormat('Y-m-d',$data['working_day_end'])->format('Y-m-d 23:59:59');
      
                $oSelect->whereBetween("sf_time_working_staffs.working_day" , [$start, $end] );
                $oSelect->where("sf_time_working_staffs.staff_id" , '=', Auth()->id() ?? 40 );
            }
    
            
         
        return $oSelect->get();
    }

}