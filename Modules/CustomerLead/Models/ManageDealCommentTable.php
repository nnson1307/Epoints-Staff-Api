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

class ManageDealCommentTable extends Model
{
    protected $table = "cpo_deal_comment";
    protected $primaryKey = "deal_comment_id";
     public function getComment($dealId){
         $oSelect = $this
             ->select(
                 "{$this->table}.deal_comment_id",
                 "{$this->table}.deal_id",
                 "{$this->table}.parent_deal_comment_id",
                 "{$this->table}.message",
                 "{$this->table}.staff_id",
                 "staffs.full_name as staff_name",
                 "staffs.staff_avatar",
                 "{$this->table}.created_at",
                 "{$this->table}.created_by as created_by_id",
                 "{$this->table}.path"

             )
             ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id")
             ->where("{$this->table}.deal_id",$dealId)
         ->orderBy("{$this->table}.created_at",'desc');
         return $oSelect->get();
    }
    public function createdComment($createdComment){
         return $this
             ->insertGetId($createdComment);

    }
    public function deleteMessageDeal($id){
        return $this->where("{$this->table}.deal_comment_id",$id)->delete();
    }
}