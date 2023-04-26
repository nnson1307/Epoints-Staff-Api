<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class StaffsTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";
    protected $fillable = [
        "staff_id",
        "full_name",
        "staff_avatar",
        "staff_title",
        "staff_title_id",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = [])
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name',
                $this->table . '.staff_avatar',
                $this->table . '.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id");

        if (isset($data['time_off_type_id'])) {
            $id = $data['time_off_type_id'];
            $oSelect->where("{$this->table}.time_off_type_id", $id);
        }
        return $oSelect->get();
    }

    public function getDetail($staffId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name',
                $this->table . '.staff_avatar',
                $this->table . '.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id");
        $oSelect->where("{$this->table}.staff_id", $staffId);

        return $oSelect->first();
    }

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

    public function getStaffApproveInfo($staffTitleId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name',
                $this->table . '.staff_avatar',
                $this->table . '.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id")
            // ->where("s.department_id", "=",  Auth()->user()->department_id ?? 1)
            ->where("st.staff_title_id", "=",  $staffTitleId);

        return $oSelect->first();
    }

    public function getDetailStaffApproveInfo($staffId)
     {
         $oSelect = $this
             ->select(
                 $this->table . '.staff_id',
                 $this->table . '.full_name',
                 $this->table . '.staff_avatar',
                 $this->table . '.staff_title_id',
                 $this->table . '.department_id',
                 'st.staff_title_name as staff_title',
                 'dep.department_name',
             )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
             ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id")
             ->where("{$this->table}.staff_id", "=",  $staffId);
 
         return $oSelect->first();
     }

      /**
     * Chi tiết
     *
     * @param $id
     * @return mixed
     */
    public function getDetailApproveLevel1($departmentId)
    {
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name',
                $this->table.'.staff_avatar',
                $this->table.'.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
                'st.is_manager'
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id");
            $oSelect->where("{$this->table}.department_id", $departmentId);
            $oSelect->where("st.is_manager", 1);
        return $oSelect->first();
    }
}
