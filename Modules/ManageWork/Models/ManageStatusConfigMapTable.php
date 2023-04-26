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

class ManageStatusConfigMapTable extends Model
{
    protected $table = "manage_status_config_map";
    protected $primaryKey = "manage_status_config_map_id";

//    Lấy danh sách trạng thái kế tiếp
    public function getListStatusByConfig($manage_status_id){
        return $this
            ->select(
                $this->table.'.manage_status_id',
                DB::raw("IF(manage_status_config_map.manage_status_id = 7 ,1,0) as is_cancel")
            )
            ->join('manage_status_config','manage_status_config.manage_status_config_id',$this->table.'.manage_status_config_id')
            ->where('manage_status_config.manage_status_id',$manage_status_id)
            ->get();
    }

}