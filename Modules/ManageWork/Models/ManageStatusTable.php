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

class ManageStatusTable extends Model
{
    protected $table = "manage_status";
    protected $primaryKey = "manage_status_id";

    /**
     * Danh sách trạng thái
     */
    public function getListStatus($arrStatus = []){

        $oSelect = $this
            ->select(
            "{$this->table}.manage_status_id",
            "{$this->table}.manage_status_name",
            "{$this->table}.manage_status_color",
            DB::raw("IF(manage_status.manage_status_id = 7 ,1,0) as is_cancel")
        )
            ->join("manage_status_config","{$this->table}.manage_status_id","manage_status_config.manage_status_id")
            ->where('manage_status_config.is_active',1);

        if (count($arrStatus) != 0){
            $oSelect = $oSelect->whereIn($this->table.'.manage_status_id',$arrStatus);
        }

        $oSelect = $oSelect->get();

        return $oSelect;
    }

    /**
     * Lấy danh sách trạng thái không có hoàn thành
     */
    public function getListStatusNotFinish(){
        $oSelect = $this->select('manage_status_id','manage_status_name','manage_status_color',DB::raw("IF(manage_status_id = 7 ,1,0) as is_cancel"))->where('manage_status_id','<>',6)->where('is_active',1)->get();

        return $oSelect;
    }

    public function getItem($id)
    {
        $oSelect = $this
            ->select('*',DB::raw("IF(manage_status_id = 7 ,1,0) as is_cancel"))
            ->where($this->primaryKey, $id)->first();

        return $oSelect;
    }

}