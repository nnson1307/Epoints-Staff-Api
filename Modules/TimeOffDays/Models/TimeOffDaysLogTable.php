<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class TimeOffDaysLogTable extends Model
{
    protected $table = "time_off_days_log";
    protected $primaryKey = "time_off_days_log_id";
    public $timestamps = false;
    protected $fillable = [
        "time_off_days_log_id",
        "time_off_days_action",
        "time_off_days_content",
        "time_off_days_id",
        "time_off_days_title",
        "created_at",
        "created_by",
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
                $this->table.'.time_off_days_log_id',
                $this->table.'.time_off_days_action',
                DB::raw('CONCAT(full_name, " ", time_off_days_content) AS time_off_days_content'),
                // $this->table.'.time_off_days_content',
                $this->table.'.time_off_days_title',
                $this->table.'.time_off_days_id',
                $this->table.'.created_at',
                's.staff_avatar',
                's.full_name as full_name',
            )
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.created_by");
        if (isset($data['time_off_days_id'])) {
            $id = $data['time_off_days_id'];
            $oSelect->where($this->table.".time_off_days_id", "=",  $id);
        }

        // if (isset($data['created_by'])) {
        //     $createdBy = $data['created_by'];
        //     $oSelect->where($this->table.".created_by", "=",  $createdBy);
        // }

        $oSelect->orderBy('time_off_days_log_id', 'DESC');
        
        return $oSelect->get();
        // get số trang
        // $page = (int)($data['page'] ?? 1);
        // return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thêm log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $add = $this->create($data);
     
        return $add->time_off_days_log_id;
    }

}