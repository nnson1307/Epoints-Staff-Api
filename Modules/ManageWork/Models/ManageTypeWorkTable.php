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

class ManageTypeWorkTable extends Model
{
    protected $table = "manage_type_work";
    protected $primaryKey = "manage_type_work_id";

    /**
     * Tạo loại công việc
     */
    public function createdTypeWork($data){
        return $this->insertGetId($data);
    }

    /**
     * Danh sách loại công việc
     */
    public function getAll(){
        return $this
            ->select(
                'manage_type_work_id',
                'manage_type_work_name',
                'manage_type_work_icon'
            )
            ->where('is_active',1)
            ->orderBy('created_at','DESC')
            ->get();
    }

}