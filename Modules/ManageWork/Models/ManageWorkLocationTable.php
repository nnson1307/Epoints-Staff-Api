<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/09/2022
 * Time: 14:17
 */

namespace Modules\ManageWork\Models;


use Illuminate\Database\Eloquent\Model;

class ManageWorkLocationTable extends Model
{
    protected $table = "manage_work_location";
    protected $primaryKey = "manage_work_location_id";
    protected $fillable = [
        "manage_work_location_id",
        "manage_work_id",
        "staff_id",
        "lat",
        "lng",
        "description",
        "is_deleted",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Thêm vị trí làm việc
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->manage_work_location_id;
    }

    /**
     * Chỉnh sửa vị trí làm việc
     *
     * @param array $data
     * @param $locationId
     * @return mixed
     */
    public function edit(array $data, $locationId)
    {
        return $this->where("manage_work_location_id", $locationId)->update($data);
    }

    /**
     * Lấy vị trí công việc
     *
     * @param $manageWorkId
     * @return mixed
     */
    public function getLocation($manageWorkId)
    {
        return $this
            ->select(
                "{$this->table}.manage_work_location_id",
                "{$this->table}.manage_work_id",
                "{$this->table}.staff_id",
                "s.full_name as staff_name",
                "s.staff_avatar",
                "{$this->table}.lat",
                "{$this->table}.lng",
                "{$this->table}.created_at",
                "{$this->table}.description"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.manage_work_id", $manageWorkId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.manage_work_location_id", "desc")
            ->get();
    }

    /**
     * Lấy thông tin địa chỉ công việc
     *
     * @param $locationId
     * @return mixed
     */
    public function getInfo($locationId)
    {
        return $this
            ->select(
                "{$this->table}.manage_work_location_id",
                "{$this->table}.manage_work_id",
                "{$this->table}.staff_id",
                "s.full_name as staff_name",
                "s.staff_avatar",
                "{$this->table}.lat",
                "{$this->table}.lng",
                "{$this->table}.created_at",
                "{$this->table}.description"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.manage_work_location_id", $locationId)
            ->first();
    }
}