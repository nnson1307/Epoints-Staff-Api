<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-07
 * Time: 6:12 PM
 * @author SonDepTrai
 */

namespace Modules\Home\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomerAppointmentTable extends Model
{
    protected $table = 'customer_appointments';
    protected $primaryKey = 'customer_appointment_id';
    protected $fillable = [
        'customer_appointment_id',
        'customer_appointment_code',
        'customer_id',
        'branch_id',
        'customer_refer',
        'appointment_source_id',
        'customer_appointment_type',
        'date',
        'time',
        'description',
        'status',
        'customer_quantity',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    /**
     * Danh sách lịch sử đặt lịch hẹn
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getAppointments($filter, $customerId)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "branches.branch_name",
//                "appointment_source.appointment_source_name",
                "{$this->table}.customer_appointment_type",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.status",
                "{$this->table}.customer_quantity"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
//            ->leftJoin("appointment_source", "appointment_source.appointment_source_id", "=", "{$this->table}.appointment_source_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("branches.is_deleted", 0)
//            ->where("appointment_source.is_deleted", 0)
            ->orderBy('date', "asc")
            ->orderBy('time', "asc");

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter type
        if ($filter['type'] == "current") {
            $ds->where(function ($query) {
                $query->where("{$this->table}.date", ">=", date('Y-m-d'))
                    ->whereIn("{$this->table}.status", ['new', 'confirm', 'wait']);
            });

        } else if ($filter['type'] == "older") {
            $ds->where(function ($query) {
                $query->where("{$this->table}.date", "<", date('Y-m-d'))
                    ->orWhereIn("{$this->table}.status", ['finish', 'cancel']);
            });
        }

        // filter branch
        if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        // filter created at
        if (isset($filter['created_at']) && $filter['created_at'] != null) {
            $arr_filter = explode(" - ", $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thông tin lịch sử lịch hẹn
     *
     * @param $appointmentId
     * @param $customerId
     * @return mixed
     */
    public function appointmentInfo($appointmentId, $customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "branches.branch_name",
                "appointment_source.appointment_source_name",
                "{$this->table}.customer_appointment_type",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.status",
                "{$this->table}.customer_quantity"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("appointment_source", "appointment_source.appointment_source_id", "=", "{$this->table}.appointment_source_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("branches.is_deleted", 0)
            ->where("appointment_source.is_deleted", 0)
            ->where("{$this->table}.customer_appointment_id", $appointmentId)
            ->first();
    }

    /**
     * Kiểm tra số lần đặt lịch trong ngày theo chi nhánh
     *
     * @param $customerId
     * @param $date
     * @param $branchId
     * @return mixed
     */
    public function checkAppointment($customerId, $date, $branchId)
    {
        return $this
            ->select(
                "customer_appointment_id",
                "customer_appointment_code",
                "appointment_source_id",
                "customer_appointment_type",
                "date",
                "time",
                "customer_quantity",
                "description",
                "status",
                "branch_id"
            )
            ->where("date", $date)
            ->where("customer_id", $customerId)
            ->where("branch_id", $branchId)
            ->whereNotIn("status", ["finish", "cancel"])
            ->orderBy("customer_appointment_id", "desc")
            ->get();
    }

    /**
     * Thêm lịch hẹn
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_appointment_id;
    }

    /**
     * Chỉnh sửa lịch hẹn
     *
     * @param array $data
     * @param $appointmentId
     * @return mixed
     */
    public function edit(array $data, $appointmentId)
    {
        return $this->where("customer_appointment_id", $appointmentId)->update($data);
    }

    /**
     * Đếm số lịch hẹn đã được đặt trong khung giờ
     *
     * @param $date
     * @param $time
     * @return mixed
     */
    public function getAppointmentByTime($date, $time)
    {
        return $this
            ->select(
                "customer_appointment_id",
                "customer_appointment_code",
                "appointment_source_id",
                "customer_appointment_type",
                "date",
                "time",
                "customer_quantity",
                "description",
                "status"
            )
            ->where("date", $date)
            ->where("time", $time)
            ->whereIn("status", ["confirm", "wait", "new"])
            ->get()
            ->count();
    }

    /**
     * Đêm số lịch hẹn đã xóa trong ngày
     *
     * @param $date
     * @return mixed
     */
    public function numberAppointmentCancel($date)
    {
        return $this
            ->select(
                "customer_appointment_id",
                "customer_appointment_code",
                "appointment_source_id",
                "customer_appointment_type",
                "date",
                "time",
                "customer_quantity",
                "description",
                "status"
            )
            ->where("date", $date)
            ->where("status", "cancel")
            ->get()
            ->count();
    }
}