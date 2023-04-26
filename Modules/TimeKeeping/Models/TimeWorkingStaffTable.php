<?php

namespace Modules\TimeKeeping\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimeWorkingStaffTable extends Model
{
    protected $table = 'sf_time_working_staffs';
    protected $primaryKey = 'time_working_staff_id';
    protected $fillable = [
        'time_working_staff_id', 'work_schedule_id', 'shift_id', 'branch_id', 'staff_id', 'working_day', 'working_time',
        'working_end_day', 'working_end_time', 'number_working_day', 'number_working_ot_day', 'number_working_time',
        'number_working_ot_time', 'number_late_time', 'number_time_back_soon', 'is_check_in', 'is_check_out',
        'is_deducted', 'is_close', 'is_ot', 'is_off', 'is_deleted', 'created_at', 'updated_at'
    ];

    public function configs()
    {
        return $this->hasMany(
            TimeKeepingConfigTable::class,
            "branch_id",
            "branch_id"
        )->select(
            "timekeeping_config_id",
            "branch_id",
            "wifi_name",
            "wifi_ip",
            "timekeeping_type",
            "latitude",
            "longitude",
            "allowable_radius",
            "note"

        )->where("is_actived", "=", 1)
            ->where("is_deleted", "=", 0);
    }

    /**
     * Lấy ca hiện tại của staff
     * @param int|null $id
     */
    public function getCurrentShift(?int $id)
    {
        $currentDate = Carbon::now();
        $select = $this->select(
            "{$this->table}.branch_id",
            "b.branch_name",
            "{$this->table}.time_working_staff_id",
            "{$this->table}.working_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_time, "%H:%i") as working_time'),
            "{$this->table}.working_end_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_end_time, "%H:%i") as working_end_time'),
            "{$this->table}.shift_id",
            "s.shift_name",
            \DB::raw('TIME_FORMAT(s.start_work_time, "%H:%i") as start_work_time'),
            \DB::raw('TIME_FORMAT(s.end_work_time, "%H:%i") as end_work_time'),
            "{$this->table}.is_check_in",
            "{$this->table}.is_check_out",
            "ci.check_in_log_id",
            "ci.check_in_day",
            \DB::raw('TIME_FORMAT(ci.check_in_time, "%H:%i") as check_in_time'),
            "{$this->table}.number_late_time",
            "co.check_out_log_id",
            "co.check_out_day",
            \DB::raw('TIME_FORMAT(co.check_out_time, "%H:%i") as check_out_time'),
            "{$this->table}.number_time_back_soon",
            "{$this->table}.is_ot"
        )
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("sf_shifts as s", "s.shift_id", "=", "{$this->table}.shift_id")
            ->leftJoin('sf_check_in_log as ci', function ($join) {
                $join->on("ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where("ci.status", '=', CheckInLogTable::OK_STATUS);
            })
            ->leftJoin('sf_check_out_log as co', function ($join) {
                $join->on("co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where("co.status", '=', CheckOutLogTable::OK_STATUS);
            })
            ->where(function ($query) {
                $query->where("{$this->table}.is_check_in", 0)
                    ->orWhere("{$this->table}.is_check_out", 0);
            })
            ->whereRaw("'{$currentDate->format('Y-m-d')}' between {$this->table}.working_day and {$this->table}.working_end_day")
            ->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.is_deleted", 0)
            ->orderBy("{$this->table}.working_time", "ASC");

        return $select->first();
    }

    /**
     * Lấy ca đã checkin và checkout của staff
     * @param int|null $id
     * @return mixed
     */
    public function getBeforeShift(?int $id)
    {
        $currentDate = Carbon::now();
        $select = $this->select(
            "{$this->table}.branch_id",
            "b.branch_name",
            "{$this->table}.time_working_staff_id",
            "{$this->table}.working_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_time, "%H:%i") as working_time'),
            "{$this->table}.working_end_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_end_time, "%H:%i") as working_end_time'),
            "{$this->table}.shift_id",
            "s.shift_name",
            \DB::raw('TIME_FORMAT(s.start_work_time, "%H:%i") as start_work_time'),
            \DB::raw('TIME_FORMAT(s.end_work_time, "%H:%i") as end_work_time'),
            "{$this->table}.is_check_in",
            "{$this->table}.is_check_out",
            "ci.check_in_log_id",
            "ci.check_in_day",
            \DB::raw('TIME_FORMAT(ci.check_in_time, "%H:%i") as check_in_time'),
            "{$this->table}.number_late_time",
            "co.check_out_log_id",
            "co.check_out_day",
            \DB::raw('TIME_FORMAT(co.check_out_time, "%H:%i") as check_out_time'),
            "{$this->table}.number_time_back_soon",
            "{$this->table}.is_ot"
        )
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("sf_shifts as s", "s.shift_id", "=", "{$this->table}.shift_id")
            ->leftJoin('sf_check_in_log as ci', function ($join) {
                $join->on("ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where("ci.status", '=', CheckInLogTable::OK_STATUS);
            })
            ->leftJoin('sf_check_out_log as co', function ($join) {
                $join->on("co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where("co.status", '=', CheckOutLogTable::OK_STATUS);
            })
            ->where("{$this->table}.is_check_in", 1)
            ->Where("{$this->table}.is_check_out", 1)
            ->whereRaw("'{$currentDate->format('Y-m-d')}' between {$this->table}.working_day and {$this->table}.working_end_day")
            ->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.is_deleted", 0)
            ->orderBy("{$this->table}.working_time", "DESC");
        return $select->first();
    }

    /**
     * Lấy chi tiết chấm công
     * @param int|null $id
     * @param $time_working_staff_id
     * @return mixed
     */
    public function getTimeWorking(?int $id, $time_working_staff_id)
    {
        return $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.number_late_time",
                "{$this->table}.number_time_back_soon",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                //                    "{$this->table}.is_off",
                "{$this->table}.is_deleted",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.time_work",
                "ci.check_in_day",
                "ci.check_in_time"
            )
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->with("configs")
            ->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.time_working_staff_id", $time_working_staff_id)
            ->first();
    }

    /**
     * Thực hiện checkin
     * @param int|null $id
     * @param $time_working_staff_id
     * @param array $array
     * @return mixed
     */
    public function checkIn(?int $id, $time_working_staff_id, array $array)
    {
        return $this->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.time_working_staff_id", $time_working_staff_id)
            ->update($array);
    }

    /**
     * Thực hiện checkout
     * @param int|null $id
     * @param $time_working_staff_id
     * @param array $array
     * @return mixed
     */
    public function checkOut(?int $id, $time_working_staff_id, array $array)
    {
        return $this->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.time_working_staff_id", $time_working_staff_id)
            ->update($array);
    }

    /**
     * Lấy lịch sử chẩm công của staff
     * @param array $all
     */
    public function getTimeKeepingHistories(array $all, $from_date, $to_date)
    {
        $select = $this->timeKeepingHistories($all);
        $select
            ->where(function ($query) use ($all, $from_date, $to_date) {
                $query->whereRaw("{$this->table}.working_day between '{$from_date}' and '{$to_date}'");
            })
            ->orderBy("{$this->table}.working_day", "DESC")
            ->orderBy("{$this->table}.working_time", "ASC");

        return $select->get();
    }

    /**
     * Lấy lịch sử chẩm công của staff
     * @param array $all
     */
    public function getPersonalTimeKeepingHistories($staffId, array $all)
    {

        $select = $this->timeKeepingHistories($all)
            //                       ->where("{$this->table}.working_day", "<=", Carbon::now()->format('Y-m-d'))
            ->where("{$this->table}.staff_id", $staffId);

        return $select
            ->orderBy("{$this->table}.working_day", "DESC")
            ->orderBy("{$this->table}.working_time", "ASC")
            ->get();
    }

    public function timeKeepingHistories($all)
    {
        $select = $this->select(
            "{$this->table}.branch_id",
            "b.branch_name",
            "{$this->table}.time_working_staff_id",
            "{$this->table}.working_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_time, "%H:%i") as working_time'),
            "{$this->table}.working_end_day",
            \DB::raw('TIME_FORMAT(' . $this->table . '.working_end_time, "%H:%i") as working_end_time'),
            "{$this->table}.shift_id",
            "s.shift_name",
            \DB::raw('TIME_FORMAT(s.start_work_time, "%H:%i") as start_work_time'),
            \DB::raw('TIME_FORMAT(s.end_work_time, "%H:%i") as end_work_time'),
            "{$this->table}.is_check_in",
            "{$this->table}.is_check_out",
            "ci.check_in_log_id",
            "ci.check_in_day",
            "ci.created_type",
            "ci.timekeeping_type as type_check_in",
            \DB::raw('TIME_FORMAT(ci.check_in_time, "%H:%i") as check_in_time'),
            "{$this->table}.number_late_time",
            "co.check_out_log_id",
            "co.check_out_day",
            "co.created_type",
            "co.timekeeping_type as type_check_out",
            \DB::raw('TIME_FORMAT(co.check_out_time, "%H:%i") as check_out_time'),
            "{$this->table}.number_time_back_soon",
            "st.staff_id",
            "st.full_name",
            "st.staff_avatar",
            "is_deducted",
            "{$this->table}.is_ot"
        )
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("sf_shifts as s", "s.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin('sf_check_in_log as ci', function ($join) {
                $join->on("ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where(function ($query) {
                        $query->where("ci.status", '=', CheckInLogTable::OK_STATUS)
                            ->orWhereNull("ci.status");
                    });
            })
            ->leftJoin('sf_check_out_log as co', function ($join) {
                $join->on("co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
                    ->where(function ($query) {
                        $query->where("co.status", '=', CheckInLogTable::OK_STATUS)
                            ->orWhereNull("co.status");
                    });
            })
            ->where("{$this->table}.is_deleted", 0);

        if (isset($all['from_date']) && isset($all['to_date'])) {
            $select->where(function ($query) use ($all) {
                $query->whereRaw("{$this->table}.working_day between '{$all['from_date']}' and '{$all['to_date']}'");
            });
        }

        if (isset($all['branch_id'])) {
            $select->where("{$this->table}.branch_id", $all['branch_id']);
        }

        if (isset($all['department_id'])) {
            $select->where("st.department_id", $all['department_id']);
        }

        if (isset($all['search'])) {
            $select->where('st.full_name', 'LIKE', "%{$all['search']}%");
        }

        return $select;
    }

    public function countTimeKeepingHistories(array $all)
    {
        $page = (int)($all['page'] ?? 1);
        $select = $this->select("working_day")
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("sf_shifts as s", "s.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            //                    ->where("{$this->table}.working_day", "<=", Carbon::now()->format('Y-m-d'))
            ->where("{$this->table}.is_deleted", 0)
            ->groupby("working_day");


        if (isset($all['from_date']) && isset($all['to_date'])) {
            $select->where(function ($query) use ($all) {
                $query->whereRaw("{$this->table}.working_day between '{$all['from_date']}' and '{$all['to_date']}'");
            });
        }else {
            $currentDate = Carbon::now();
            $select->where(function ($query) use ($currentDate) {
                $currentDateEnd = $currentDate->format('Y-m-d');
                $currentDateStart = $currentDate->addDay(-30)->format('Y-m-d');
                $query->whereRaw("{$this->table}.working_day between '{$currentDateStart}' and '{$currentDateEnd}'");
            });
        }

        if (isset($all['branch_id'])) {
            $select->where("{$this->table}.branch_id", $all['branch_id']);
        }

        if (isset($all['department_id'])) {
            $select->where("st.department_id", $all['department_id']);
        }

        if (isset($all['search'])) {
            $select->where('st.full_name', 'LIKE', "%{$all['search']}%");
        }
        
        return $select->orderBy("working_day", "DESC")->paginate(PAGING_ITEM_PER_PAGE, $columns = ['*'], $pageName = 'page', $page);
    }
}