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

class ManageWorkTagTable extends Model
{
    protected $table = "manage_work_tag";
    protected $primaryKey = "manage_work_tag_id";

    ///lấy tag công việc
    public function getTagWork($id){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_tag_id",
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_tag_id",
                "cpo_tag.name as tag_name"
            )
            ->leftJoin("cpo_tag", "{$this->table}.manage_tag_id","cpo_tag.tag_id")
            ->where("{$this->table}.manage_work_id",$id);
        return $mSelect->get()->toArray();
    }
}