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

class ManageCommentTable extends Model
{
    protected $table = "manage_comment";
    protected $primaryKey = "manage_comment_id";
    public function getComment($workId){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_comment_id",
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_parent_comment_id",
                "{$this->table}.message",
                "{$this->table}.staff_id",
                "staffs.full_name as created_by_name",
                "{$this->table}.created_at",
                "{$this->table}.created_by as created_by_id",
                "{$this->table}.path"

            )
            ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id")
            ->where("{$this->table}.manage_work_id",$workId)
            ->orderBy("{$this->table}.created_at",'desc');
        return $oSelect->get();
    }


}