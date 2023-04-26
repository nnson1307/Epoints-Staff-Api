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

class BranchsTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    /**
     * lấy danh sách chi nhánh
     */
    public function getListBranch($data){
        $oSelect = $this
            ->select(
                'branch_id',
                'branch_name'
            )
            ->where('is_actived',1)
            ->where('is_deleted',0);

        if (isset($data['branch_name'])){
            $oSelect = $oSelect->where('branch_name','like','%'.$data['branch_name'].'%');
        }

        if (isset($data['branch_id'])){
            $oSelect = $oSelect->where('branch_id',$data['branch_id']);
        }

        return $oSelect->orderBy('created_at','DESC')->get();
    }
}