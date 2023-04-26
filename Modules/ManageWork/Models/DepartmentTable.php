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

class DepartmentTable extends Model
{
    protected $table = "departments";
    protected $primaryKey = "department_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * lấy danh sách chi nhánh
     */
    public function getListDepartment($data){
        $oSelect = $this
            ->select(
                'department_id',
                'department_name'
            )
            ->where('is_inactive', self::IS_ACTIVE)
            ->where('is_deleted', self::NOT_DELETED);

        if (isset($data['department_name'])){
            $oSelect = $oSelect->where('department_name','like','%'.$data['department_name'].'%');
        }

        if (isset($data['department_id'])){
            $oSelect = $oSelect->where('department_id',$data['department_id']);
        }

        return $oSelect->orderBy('created_at','DESC')->get();
    }

    /**
     * Lây option chi nhánh
     *
     * @return mixed
     */
    public function getOptionDepartment()
    {
        return $this
            ->select(
                'department_id',
                'department_name'
            )
            ->where('is_inactive', self::IS_ACTIVE)
            ->where('is_deleted', self::NOT_DELETED)
            ->get();
    }
}