<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class TimeOffDaysActivityApproveTable extends Model
{
    protected $table = "time_off_days_activity_approve";
    protected $primaryKey = "time_off_days_activity_approve_id";
    protected $fillable = [
        "time_off_days_activity_approve_id",
        "time_off_days_id",
        "is_approvce",
        "time_off_days_activity_approve_note",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
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
                $this->table.'.time_off_days_activity_approve_id',
                $this->table.'.time_off_days_id',
                $this->table.'.is_approvce',
                $this->table.'.time_off_days_activity_approve_note',
            
            );
        // get số trang
        $page = (int)($data['page'] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thêm đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->time_off_days_activity_approve_id;
    }

}