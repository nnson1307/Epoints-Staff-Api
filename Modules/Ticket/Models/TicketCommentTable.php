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

class TicketCommentTable extends Model
{
    protected $table = "ticket_comment";
    protected $primaryKey = "ticket_comment_id";

    /**
     * Tạo bình luận
     */
    public function createdComment($data){
       
        return $this->insertGetId($data);
    }

    /**
     * Xoá comment
     * @param $ticket_comment_id
     * @return mixed
     */
    public function deleteComment($ticket_comment_id){
        return $this->where('ticket_comment_id',$ticket_comment_id)->delete();
    }

    /**
     * Xoá comment theo công việc
     * @param $ticket_id
     * @return mixed
     */
    public function deleteCommentByTicket($ticket_id){
        return $this->where('ticket_id',$ticket_id)->delete();
    }

    /**
     * Lấy tổng comment theo công việc
     * @param $ticket_id
     */
    public function getTotalCommentByTicket($ticket_id){
        return $this->where('ticket_id',$ticket_id)->count();
    }

    public function getListComment($ticket_id,$ticket_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_comment_id',
                $this->table.'.ticket_id',
                $this->table.'.ticket_parent_comment_id',
                $this->table.'.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'ticket_parent_comment_id as hieupc'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.ticket_id',$ticket_id);   
            if ($ticket_parent_comment_id != null){
                $oSelect = $oSelect
                    ->where($this->table.'.ticket_parent_comment_id',$ticket_parent_comment_id)
                    ->orderBy($this->table.'.created_at','ASC');
            } else {
                $oSelect = $oSelect
                    ->whereNull($this->table.'.ticket_parent_comment_id')
                    ->orderBy($this->table.'.created_at','DESC');
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
     * @param $ticket_id
     */
    public function getCommentLast($ticket_id){
        return $this
            ->leftJoin('ticket_comment as ticket_comment_parent','ticket_comment_parent.ticket_comment_id',$this->table.'.ticket_parent_comment_id')
            ->where($this->table.'.ticket_id',$ticket_id)
            ->select(
                $this->table.'.updated_by',
                'ticket_comment_parent.updated_by as updated_by_parent'
            )
            ->orderBy($this->table.'.ticket_comment_id','DESC')
            ->first();
    }

    /**
     * Chi tiết comment
     * @param $ticket_work_id
     * @param null $ticket_comment_id
     * @return mixed
     */
    public function getDetail($ticket_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_comment_id',
                $this->table.'.ticket_id',
                $this->table.'.ticket_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "t.customer_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("ticket as t", "t.ticket_id", "=", "{$this->table}.ticket_id")
            ->where($this->table.'.ticket_comment_id',$ticket_comment_id);
        return $oSelect->first();
    }
}