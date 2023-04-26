<?php

/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-07
 * Time: 6:12 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'updated_at',
        'total',
        'discount',
        'amount',
        'voucher_code',
        'service_using_name'
    ];

    protected $casts = [
        'total' => 'float',
        'discount' => 'float',
        'amount' => 'float'
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách lịch sử đặt lịch hẹn
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getAppointments($filter)
    {

        $ds = $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.customer_id",
                "branches.branch_name",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.status",
                "{$this->table}.service_using_name",
                "c.full_name as customer_name",
                "l.status_primary_color",
                "l.status_sub_color",
                "l.status_text_color",
                DB::raw("COUNT(d.staff_id) count_staff")
            )
            ->join("appointment_status_color as l", "l.status", "=", "{$this->table}.status")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->join("customers as c", "c.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customer_appointment_details as d", "d.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->where("branches.is_deleted", self::NOT_DELETED);
        if (isset($filter['staff_id'])) {
            //Tuỳ chọn nv
            if ($filter['staff_id'] != null && count($filter['staff_id']) > 0) {
                $ds->whereIn("d.staff_id", $filter['staff_id']);
            }
            //Không có nhân viên
            if (!is_array($filter['staff_id']) && $filter['staff_id'] == null) {
                $ds->havingRaw("count_staff = 0");
            }
        }

        //Phân quyền data
        if (Auth::user()->is_admin != 1) {
            $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
        }

        // filter branch
        if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        // filter created at
        if (
            isset($filter['date_start']) && $filter['date_start'] != null
            && isset($filter['date_end']) && $filter['date_end'] != null
        ) {
            $startTime = Carbon::createFromFormat('d/m/Y', $filter['date_start'])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $filter['date_end'])->format('Y-m-d');

            $ds->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }


        return $ds->groupby("{$this->table}.customer_appointment_id")->get();
    }

    /**
     * Lấy lịch sử đặt lịch của khách hàng
     *
     * @param $filter
     * @return mixed
     */
    public function getBookingCustomer($filter)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.customer_id",
                "branches.branch_name",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.status",
                "{$this->table}.service_using_name",
                "c.full_name as customer_name",
                "l.status_primary_color",
                "l.status_sub_color",
                "l.status_text_color",
                DB::raw("COUNT(d.staff_id) count_staff")
            )
            ->join("appointment_status_color as l", "l.status", "=", "{$this->table}.status")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->join("customers as c", "c.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customer_appointment_details as d", "d.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->where("branches.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.customer_id", $filter['customer_id'])
            ->groupby("{$this->table}.customer_appointment_id");

        //Phân quyền data user
        if (Auth::user()->is_admin != 1) {
            $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
        }

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * DS lịch hẹn theo khung giờ
     *
     * @param $filter
     * @return mixed
     */
    public function getAppointmentRangeTime($filter)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "branches.branch_name",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.status",
                "{$this->table}.service_using_name",
                "c.full_name as customer_name",
                "l.status_primary_color",
                "l.status_sub_color",
                "l.status_text_color",
                "{$this->table}.customer_id"
            )
            ->join("appointment_status_color as l", "l.status", "=", "{$this->table}.status")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->join("customers as c", "c.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customer_appointment_details as d", "d.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->where("branches.is_deleted", self::NOT_DELETED);

        //Tuỳ chọn nv
        if ($filter['staff_id'] != null && count($filter['staff_id']) > 0) {
            $ds->whereIn("d.staff_id", $filter['staff_id']);
        }

        //Không có nhân viên
        if (!is_array($filter['staff_id']) && $filter['staff_id'] == null) {
            $ds->havingRaw("count_staff = 0");
        }

        // filter branch
        if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        // filter date
        if (isset($filter['date']) && $filter['date'] != null) {
            $startTime = Carbon::createFromFormat('d/m/Y', $filter['date'])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $filter['date'])->format('Y-m-d');

            $ds->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        // filter time
        if (isset($filter['time_start']) && $filter['time_start'] != null && isset($filter['time_end']) && $filter['time_end'] != null) {
            $startTime = Carbon::createFromFormat('H:i', $filter['time_start'])->format('H:i:s');
            $endTime = Carbon::createFromFormat('H:i', $filter['time_end'])->format('H:i:s');

            $ds->whereBetween("{$this->table}.time", [$startTime, $endTime]);
        }

        //Phân quyền data user
        if (Auth::user()->is_admin != 1) {
            $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
        }

        return $ds->groupby("{$this->table}.customer_appointment_id")->get();
    }

    /**
     * Thông tin lịch sử lịch hẹn
     *
     * @param $appointmentId
     * @param $customerId
     * @return mixed
     */
    public function appointmentInfo($appointmentId)
    {
        return $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.branch_id",
                "{$this->table}.customer_id",
                "branches.branch_name",
                "appointment_source.appointment_source_name",
                "{$this->table}.customer_appointment_type",
                "{$this->table}.date",
                DB::raw("DATE_FORMAT({$this->table}.time, '%H:%i') as time"),
                "{$this->table}.status",
                "l.status_primary_color",
                "l.status_sub_color",
                "l.status_text_color",
                "{$this->table}.customer_quantity",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                DB::raw("CONCAT(province.type, ' ', province.name) as branch_province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as branch_district_name"),
                "branches.address as branch_address",
                "{$this->table}.created_at",
                "cs.full_name as customer_name",
                "cs.email",
                "cs.phone1 as phone",
                "cs.birthday",
                "cs.customer_avatar",
                "g.group_name",
                "p.type as province_type",
                "p.name as province_name",
                "d.type as district_type",
                "d.name as district_name",
                "w.name as ward_name",
                "w.type as ward_type",
                "cs.address",
                "{$this->table}.description",
                "{$this->table}.appointment_source_id",
                "{$this->table}.service_using_name"
            )
            ->join("appointment_status_color as l", "l.status", "=", "{$this->table}.status")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customer_groups as g", "g.customer_group_id", "=", "cs.customer_group_id")
            ->leftJoin("appointment_source", "appointment_source.appointment_source_id", "=", "{$this->table}.appointment_source_id")
            ->leftJoin("province", "province.provinceid", "=", "branches.provinceid")
            ->leftJoin("district", "district.districtid", "=", "branches.districtid")
            ->leftJoin("province as p", "p.provinceid", "=", "cs.province_id")
            ->leftJoin("district as d", "d.districtid", "=", "cs.district_id")
            ->leftJoin("ward as w", "w.ward_id", "=", "cs.ward_id")
            ->where("branches.is_deleted", 0)
            //            ->where("appointment_source.is_deleted", 0)
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

    /**
     * Thông tin lịch sử lịch hẹn
     *
     * @param $appointmentCode
     * @param $customerId
     * @return mixed
     */
    public function appointmentInfoByCode($appointmentCode, $customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "branches.branch_name",
                //                "appointment_source.appointment_source_name",
                "{$this->table}.customer_appointment_type",
                "{$this->table}.date",
                DB::raw("DATE_FORMAT({$this->table}.time, '%H:%i') as time"),
                "{$this->table}.status",
                "{$this->table}.customer_quantity",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                DB::raw("CONCAT(province.type, ' ', province.name) as branch_province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as branch_district_name"),
                "branches.address as branch_address",
                "{$this->table}.created_at"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            //            ->leftJoin("appointment_source", "appointment_source.appointment_source_id", "=", "{$this->table}.appointment_source_id")
            ->leftJoin("province", "province.provinceid", "=", "branches.provinceid")
            ->leftJoin("district", "district.districtid", "=", "branches.districtid")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("branches.is_deleted", 0)
            //            ->where("appointment_source.is_deleted", 0)
            ->where("{$this->table}.customer_appointment_code", $appointmentCode)
            ->first();
    }

    /**
     * Lấy thông tin LH gửi sms
     *
     * @param $appointmentId
     * @return mixed
     */
    public function getInfoSendSms($appointmentId)
    {
        return $this
            ->select(
                "cs.full_name",
                "cs.phone1 as phone",
                "cs.gender",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.customer_appointment_id"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.customer_appointment_id", $appointmentId)
            ->first();
    }
}
