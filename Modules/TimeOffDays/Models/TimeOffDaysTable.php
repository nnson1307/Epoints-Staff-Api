<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class TimeOffDaysTable extends Model
{

    protected $table = "time_off_days";
    protected $primaryKey = "time_off_days_id";
    protected $fillable = [
        "time_off_days_id",
        "time_off_type_id",
        "time_off_days_start",
        "time_off_days_end",
        "time_off_days_time",
        "time_off_note",
        "staff_id_level1",
        "staff_id_level2",
        "staff_id_level3",
        "staff_id",
        "is_approve_level1",
        "is_approve_level2",
        "is_approve_level3",
        "is_approve",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        'date_type_select'
    ];

    /**
     * Get danh sách ngày phép
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){

       
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_id',
                $this->table.'.time_off_type_id',
                $this->table.'.time_off_days_start',
                $this->table.'.time_off_days_end',
                $this->table.'.time_off_note',
                $this->table.'.time_off_days_time',
                $this->table.'.created_at',
                $this->table.'.created_by',
                $this->table.'.staff_id_level1',
                $this->table.'.staff_id_level2',
                $this->table.'.staff_id_level3',
                $this->table.'.is_approve_level1',
                $this->table.'.is_approve_level2',
                $this->table.'.is_approve_level3',
                $this->table.'.is_approve as is_approvce',
                $this->table.'.date_type_select',
                // 'ap.is_approvce',
                's.staff_avatar',
                's.full_name',
                'tot.time_off_type_name',
                "tot.direct_management_approve",
                "tot.staff_id_approve_level2",
                "tot.staff_id_approve_level3",
                'dep.department_name',
                'dep.department_id'
            )
            ->leftJoin("time_off_days_activity_approve as ap", "ap.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->leftJoin("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "s.department_id")
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "s.staff_title_id")
            ->where("{$this->table}.is_deleted", "=", 0);

            // null
            if (isset($data['is_approvce'])){
                $isApprovce = $data['is_approvce'];
                if(count($isApprovce) == 2)
                {
                    $oSelect->where("{$this->table}.is_approve", "!=", null);
                }else{
                    $oSelect->where("{$this->table}.is_approve", "=", $isApprovce[0]);
                }
            }else{
                $oSelect->whereNull("{$this->table}.is_approve");
            }

            // if (isset($data['time_off_type_id'])) {
            //     $typeIds = $data['time_off_type_id'];
            //     $oSelect->whereIn("{$this->table}.time_off_type_id", $typeIds);
            // }
            // if (isset($data['time_off_days_start'])) {
            //     $start = $data['time_off_days_start'];
            //     $oSelect->where("{$this->table}.time_off_days_start", ">=", $start);
            // }
            // if (isset($data['time_off_days_end'])) {
            //     $end = $data['time_off_days_end'];
            //     $oSelect->where("{$this->table}.time_off_days_end", "<=", $end);
            // }
            
            // if (isset($data['created_by'])) {
            //     $createdBy = $data['created_by'];
            //     $oSelect->where("{$this->table}.staff_id", "=", $createdBy);
            // }

            $oSelect->orderBy('time_off_days_id', 'DESC');
            
        // get số trang
        $page = (int)($data['page'] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thêm danh sách ngày phép
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $data['created_by'] = Auth()->id();
        $data['staff_id'] = Auth()->id();
        $add = $this->create($data);

        return $add->time_off_days_id;
    }


     /**
     * Chi tiết ngày phép
     *
     * @param array $data
     * @return mixed
     */
    public function detail($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_days_id",
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_days_start",
                "{$this->table}.time_off_days_end",
                "{$this->table}.time_off_note",
                "{$this->table}.created_at",
                "{$this->table}.time_off_days_time",
                "{$this->table}.staff_id_level1",
                "{$this->table}.staff_id_level2",
                "{$this->table}.staff_id_level3",
                "{$this->table}.is_approve_level1",
                "{$this->table}.is_approve_level2",
                "{$this->table}.is_approve_level3",
                "{$this->table}.is_approve as is_approvce",
                $this->table.'.date_type_select',
                "tot.time_off_type_name",
                "tot.time_off_type_code",
                "tot.time_off_type_description",
                "tot.direct_management_approve",
                "tot.staff_id_approve_level2",
                "tot.staff_id_approve_level3",
                's.staff_avatar',
                's.full_name',
                'tot.time_off_type_name',
                'dep.department_name',
                'dep.department_id'
            )

            ->leftJoin("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->leftJoin("time_off_days_files as todf", "todf.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->leftJoin("time_off_days_activity_approve as ap", "ap.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "s.department_id")
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "s.staff_title_id")

            ->where("{$this->table}.time_off_days_id", $id)
            ->where("{$this->table}.is_deleted", 0)

            ->first();
    }

    /**
     * Xóa đơn phép
     *
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        $data['is_deleted'] = 1;
        return $this->where("time_off_days_id", $id)->update($data);
    }

    /**
     * Chỉnh sửa đơn phép
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        $data['updated_by'] = Auth()->id();
        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        return $this->where($this->primaryKey, $id)->update($data);
    }

     /**
     * Tổng ngày phép
     *
     * @param array $data
     * @return mixed
     */
    public function total($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_days_id",
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_days_start",
                "{$this->table}.time_off_days_end",
                "{$this->table}.time_off_note",
                "{$this->table}.created_at",
                "{$this->table}.time_off_days_time",
                "{$this->table}.staff_id_level1",
                "{$this->table}.staff_id_level2",
                "{$this->table}.staff_id_level3",
                "{$this->table}.is_approve_level1",
                "{$this->table}.is_approve_level2",
                "{$this->table}.is_approve_level3",
            )

            ->where("{$this->table}.created_by", $id)
            ->where("{$this->table}.is_deleted", 0)

            ->get();
    }

     /**
     * Tổng ngày phép
     *
     * @param array $data
     * @return mixed
     */
    public function countById($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_days_id",
            )
            ->whereNull("is_approve")
            ->Where(function ($q) use ($id)  {
                $q->whereNull("is_approve_level1")->Where("staff_id_level1", $id);
                $q->whereNull("is_approve_level2")->Where("staff_id_level2", $id);
                $q->whereNull("is_approve_level3")->Where("staff_id_level3", $id);
            })
            ->where("{$this->table}.is_deleted", 0)
            ->count();
    }
}