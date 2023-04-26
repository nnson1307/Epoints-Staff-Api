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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageDocumentTable extends Model
{
    protected $table = "manage_document";
    protected $primaryKey = "manage_document_id";

    /**
     * Xoá doc theo công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteDocumentByWork($manage_work_id){
        return $this
            ->where('manage_work_id',$manage_work_id)
            ->delete();
    }

    /**
     * Lấy danh sách hồ sơ
     * @param $manage_work_id
     * @return mixed
     */
    public function getListDocument($manage_work_id){
        return $this
            ->select(
                'manage_document_id',
                'manage_document_title'
            )
            ->where('manage_work_id',$manage_work_id)
            ->orderBy('created_at','DESC')
            ->get();
    }
}