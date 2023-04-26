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

class ManageCommentTable extends Model
{
    protected $table = "manage_comment";
    protected $primaryKey = "manage_comment_id";

    /**
     * Tạo bình luận
     */
    public function createdComment($data){
        return $this->insert($data);
    }

    /**
     * Xoá comment
     * @param $manage_comment_id
     * @return mixed
     */
    public function deleteComment($manage_comment_id){
        return $this->where('manage_comment_id',$manage_comment_id)->delete();
    }

    /**
     * Xoá comment theo công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteCommentByWork($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->delete();
    }

    /**
     * Lấy tổng comment theo công việc
     * @param $manage_work_id
     */
    public function getTotalCommentByWork($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->count();
    }

    public function getListComment($manage_work_id,$manage_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.manage_comment_id',
                $this->table.'.manage_work_id',
                $this->table.'.manage_parent_comment_id',
                $this->table.'.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_work_id',$manage_work_id);

        if ($manage_parent_comment_id == null) {
            $oSelect = $oSelect->whereNull('manage_parent_comment_id');
        } else {
            $oSelect = $oSelect->where('manage_parent_comment_id',$manage_parent_comment_id);
        }

        $oSelect = $oSelect->orderBy($this->table.'.created_at','ASC')->get();

        if (count($oSelect) != 0){
            foreach ($oSelect as $key => $item){
                $oSelect[$key]['time_text'] = Carbon::parse($item['created_at'])->diffForHumans(Carbon::now());
                unset($oSelect[$key]['created_at']);
            }
        }

        return $oSelect;
    }

    /**
     * Lấy comment mới nhất
     * @param $manage_work_id
     */
    public function getCommentLast($manage_work_id){
        return $this
            ->leftJoin('manage_comment as manage_comment_parent','manage_comment_parent.manage_comment_id',$this->table.'.manage_parent_comment_id')
            ->where($this->table.'.manage_work_id',$manage_work_id)
            ->select(
                $this->table.'.updated_by',
                'manage_comment_parent.updated_by as updated_by_parent'
            )
            ->orderBy($this->table.'.manage_comment_id','DESC')
            ->first();
    }


}