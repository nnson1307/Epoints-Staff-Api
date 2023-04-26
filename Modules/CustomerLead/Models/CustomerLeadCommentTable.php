<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerLeadCommentTable extends Model
{
    protected $table = "cpo_customer_lead_comment";
    protected $primaryKey = "customer_lead_comment_id";

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
    public function deleteComment($customer_lead_comment_id){
        return $this->where('customer_lead_comment_id',$customer_lead_comment_id)->delete();
    }

    /**
     * Xoá comment theo công việc
     * @param $customer_id
     * @return mixed
     */
    public function deleteCommentByCustomer($customer_lead_id){
        return $this->where('customer_lead_id',$customer_lead_id)->delete();
    }

    /**
     * Lấy tổng comment theo công việc
     * @param $customer_id
     */
    public function getTotalCommentByCustomer($customer_lead_id){
        return $this->where('customer_lead_id',$customer_lead_id)->count();
    }

    public function getListComment($customer_lead_id,$customer_lead_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_comment_id',
                $this->table.'.customer_lead_id',
                $this->table.'.customer_lead_parent_comment_id',
                $this->table.'.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.customer_lead_id',$customer_lead_id);   
            if ($customer_lead_parent_comment_id != null){
                $oSelect = $oSelect
                    ->where($this->table.'.customer_lead_parent_comment_id',$customer_lead_parent_comment_id)
                    ->orderBy($this->table.'.created_at','ASC');
            } else {
                $oSelect = $oSelect
                    ->whereNull($this->table.'.customer_lead_parent_comment_id')
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
     * Chi tiết comment
     * @return mixed
     */
    public function getDetail($customer_lead_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_comment_id',
                $this->table.'.customer_lead_id',
                $this->table.'.customer_lead_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "t.customer_lead_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("customers as t", "t.customer_lead_id", "=", "{$this->table}.customer_lead_id")
            ->where($this->table.'.customer_lead_comment_id',$customer_lead_comment_id);
        return $oSelect->first();
    }
}