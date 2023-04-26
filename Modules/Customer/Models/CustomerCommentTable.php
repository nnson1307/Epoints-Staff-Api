<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Customer\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerCommentTable extends Model
{
    protected $table = "customers_comment";
    protected $primaryKey = "customer_comment_id";

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
    public function deleteComment($customer_comment_id){
        return $this->where('customer_comment_id',$customer_comment_id)->delete();
    }

    /**
     * Xoá comment theo công việc
     * @param $customer_id
     * @return mixed
     */
    public function deleteCommentByCustomer($customer_id){
        return $this->where('customer_id',$customer_id)->delete();
    }

    /**
     * Lấy tổng comment theo công việc
     * @param $customer_id
     */
    public function getTotalCommentByCustomer($customer_id){
        return $this->where('customer_id',$customer_id)->count();
    }

    public function getListComment($customer_id,$customer_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.customer_comment_id',
                $this->table.'.customer_id',
                $this->table.'.customer_parent_comment_id',
                $this->table.'.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.customer_id',$customer_id);   
            if ($customer_parent_comment_id != null){
                $oSelect = $oSelect
                    ->where($this->table.'.customer_parent_comment_id',$customer_parent_comment_id)
                    ->orderBy($this->table.'.created_at','ASC');
            } else {
                $oSelect = $oSelect
                    ->whereNull($this->table.'.customer_parent_comment_id')
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
    public function getCommentLast($customer_id){
        return $this
            ->leftJoin('customer_comment as ticket_comment_parent','customer_comment_parent.ticket_comment_id',$this->table.'.ticket_parent_comment_id')
            ->where($this->table.'.customer_id',$customer_id)
            ->select(
                $this->table.'.updated_by',
                'customer_comment_parent.updated_by as updated_by_parent'
            )
            ->orderBy($this->table.'.customer_comment_id','DESC')
            ->first();
    }

    /**
     * Chi tiết comment
     * @return mixed
     */
    public function getDetail($customer_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_comment_id',
                $this->table.'.customer_id',
                $this->table.'.customer_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "t.customer_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("customers as t", "t.customer_id", "=", "{$this->table}.customer_id")
            ->where($this->table.'.customer_comment_id',$customer_comment_id);
        return $oSelect->first();
    }
}