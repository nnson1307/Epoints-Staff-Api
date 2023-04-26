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

class ManageLeadCommentTable extends Model
{
    protected $table = "cpo_customer_lead_comment";
    protected $primaryKey = "customer_lead_comment_id";
     public function getComment($customerLeadId){
         $oSelect = $this
             ->select(
                 "{$this->table}.customer_lead_comment_id",
                 "{$this->table}.customer_lead_id",
                 "{$this->table}.customer_lead_parent_comment_id",
                 "{$this->table}.message",
                 "{$this->table}.staff_id",
                 "staffs.full_name as staff_name",
                 "staffs.staff_avatar",
                 "{$this->table}.created_at",
                 "{$this->table}.created_by as created_by_id",
                 "{$this->table}.path"

             )
             ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id")
             ->where("{$this->table}.customer_lead_id",$customerLeadId)
         ->orderBy("{$this->table}.created_at",'desc');
         return $oSelect->get()->toArray();
    }
    public function createdComment($createdComment){
         return $this
             ->insertGetId($createdComment);

    }
    public function deleteMessageLead($id){
        return $this->where("{$this->table}.customer_lead_comment_id",$id)->delete();
    }


}