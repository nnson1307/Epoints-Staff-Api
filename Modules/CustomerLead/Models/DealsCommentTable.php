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

class DealsCommentTable extends Model
{
    protected $table = "cpo_deals_comment";
    protected $primaryKey = "deal_comment_id";

    /**
     * Tạo bình luận
     */
    public function createdComment($data)
    {

        return $this->insertGetId($data);
    }

    /**
     * Xoá comment
     * @param $ticket_comment_id
     * @return mixed
     */
    public function deleteComment($deal_comment_id)
    {
        return $this->where('deal_comment_id', $deal_comment_id)->delete();
    }

    /**
     * Xoá comment theo công việc
     * @param $customer_id
     * @return mixed
     */
    public function deleteCommentByCustomer($deal_id)
    {
        return $this->where('deal_id', $deal_id)->delete();
    }

    /**
     * Lấy tổng comment theo công việc
     * @param $customer_id
     */
    public function getTotalCommentByCustomer($deal_id)
    {
        return $this->where('deal_id', $deal_id)->count();
    }

    public function getListComment($deal_id, $deal_parent_comment_id = null)
    {
        $oSelect = $this
            ->select(
                $this->table . '.deal_comment_id',
                $this->table . '.deal_id',
                $this->table . '.deal_parent_comment_id',
                $this->table . '.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                $this->table . '.message',
                $this->table . '.path',
                $this->table . '.created_at',
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->where($this->table . '.deal_id', $deal_id);
        if ($deal_parent_comment_id != null) {
            $oSelect = $oSelect
                ->where($this->table . '.deal_parent_comment_id', $deal_parent_comment_id)
                ->orderBy($this->table . '.created_at', 'ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table . '.deal_parent_comment_id')
                ->orderBy($this->table . '.created_at', 'DESC');
        }

        $oSelect = $oSelect->orderBy($this->table . '.created_at', 'ASC')->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
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
    public function getDetail($deal_comment_id)
    {
        $oSelect = $this
            ->select(
                $this->table . '.deal_comment_id',
                $this->table . '.deal_id',
                $this->table . '.deal_parent_comment_id',
                $this->table . '.staff_id',
                $this->table . '.message',
                $this->table . '.path',
                $this->table . '.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "t.deal_id"
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->join("cpo_deals as t", "t.deal_id", "=", "{$this->table}.deal_id")
            ->where($this->table . '.deal_comment_id', $deal_comment_id);
        return $oSelect->first();
    }
}
