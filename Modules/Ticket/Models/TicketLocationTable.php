<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Ticket\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketLocationTable extends Model
{
    protected $table = "ticket_location";
    protected $primaryKey = "ticket_location_id";

    protected $fillable = [
        "ticket_location_id",
        "ticket_id",
        "staff_id",
        "lat",
        "lng",
        "description",
        "is_deleted",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm location ticket
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->ticket_location_id;
    }

    /**
     * Chỉnh sửa vị trí
     *
     * @param array $data
     * @param $locationId
     * @return mixed
     */
    public function edit(array $data, $locationId)
    {
        return $this->where("ticket_location_id", $locationId)->update($data);
    }

    //    Lấy danh sách file theo loại
    public function getListLocation($ticket_id){
        $ds = $this->select(
            "{$this->table}.ticket_location_id",
            "{$this->table}.ticket_id",
            "{$this->table}.staff_id",
            "{$this->table}.lat",
            "{$this->table}.lng",
            "{$this->table}.description",
            "{$this->table}.is_deleted",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "s.full_name as staff_name",
             "s.staff_avatar",
        ) 
        ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
        ->where($this->table.'.ticket_id',$ticket_id)
        ->where("{$this->table}.is_deleted", 0)
        ->orderBy("{$this->table}.ticket_location_id", "desc")
        ->get();
           
        return $ds;
    }


    public function getInfo($id){
        $ds = $this->select(
            "{$this->table}.ticket_location_id",
            "{$this->table}.ticket_id",
            "{$this->table}.staff_id",
            "{$this->table}.lat",
            "{$this->table}.lng",
            "{$this->table}.description",
            "{$this->table}.is_deleted",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "s.full_name as staff_name",
             "s.staff_avatar",
        ) 
        ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
        ->where($this->table.'.ticket_location_id',$id)
        ->where("{$this->table}.is_deleted", 0)
        ->first();
        
        return $ds;
    }
}