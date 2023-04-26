<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class TimeOffTypeTable extends Model
{
    protected $table = "time_off_type";
    protected $primaryKey = "time_off_type_id";
    protected $fillable = [
        "time_off_type_id",
        "time_off_type_name",
        "time_off_type_parent_id",
        "time_off_type_description",
        "time_off_type_code",
        "time_off_holidays_number",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Get danh sách loại ngày phép
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_type_id',
                $this->table.'.time_off_type_name',
                $this->table.'.time_off_type_parent_id',
                $this->table.'.time_off_type_description',
                $this->table.'.time_off_type_code',
            
            )->where('time_off_type_parent_id', 0)->get();
        return $oSelect;

    }

    /**
     * Get danh sách loại ngày phép theo $parentId
     *
     * @param array $data
     * @return mixed
     */
    public function getListsChild($parentId){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_type_id',
                $this->table.'.time_off_type_name',
                $this->table.'.time_off_type_parent_id',
                $this->table.'.time_off_type_description',
                $this->table.'.time_off_type_code',
            
            )->where('time_off_type_parent_id', $parentId)->get();
        return $oSelect;

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
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_type_name",
                "{$this->table}.time_off_type_parent_id",
                "{$this->table}.time_off_type_description",
                "{$this->table}.time_off_type_code",
                "{$this->table}.is_status",
                "{$this->table}.total_number",
                "{$this->table}.month_reset"
            )
            ->where("{$this->table}.time_off_type_id", $id)
            ->first();
    }

    /**
     * Chi tiết loại
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_type_name",
                "{$this->table}.time_off_type_parent_id",
                "{$this->table}.time_off_type_description",
                "{$this->table}.time_off_type_code",
                "{$this->table}.is_status",
                "{$this->table}.total_number",
                "{$this->table}.month_reset",
                "{$this->table}.direct_management_approve",
                "{$this->table}.staff_id_approve_level2",
                "{$this->table}.staff_id_approve_level3"
            )
            ->where("{$this->primaryKey}", $id)
            ->first();
    }
}