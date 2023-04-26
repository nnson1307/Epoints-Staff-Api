<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageWorkTagTable extends Model
{
    protected $table = "manage_work_tag";
    protected $primaryKey = "manage_work_tag_id";

    /**
     * Thêm tag vào công việc
     */
    public function createdWorkTag($data){
        return $this->insert($data);
    }

    /**
     * Xoá tag theo công việc
     * @param $manageWorkId
     * @return mixed
     */
    public function deleteWorkTag($manageWorkId){
        return $this
            ->where('manage_work_id',$manageWorkId)
            ->delete();
    }

    /**
     * Lấy danh sách tag theo công việc
     * @param $manageWorkId
     * @return mixed
     */
    public function getListTagByWork($manageWorkId){
        return $this
            ->select(
               'manage_tags.manage_tag_id',
               'manage_tags.manage_tag_name',
                'manage_tags.manage_tag_icon'
            )
            ->join('manage_tags','manage_tags.manage_tag_id',$this->table.'.manage_tag_id')
            ->where($this->table.'.manage_work_id',$manageWorkId)
            ->get();
    }
}