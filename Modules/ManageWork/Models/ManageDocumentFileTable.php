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

class ManageDocumentFileTable extends Model
{
    protected $table = "manage_document_file";
    protected $primaryKey = "manage_document_file_id";

    /**
     * Danh sách file
     * @param $manage_work_id
     * @return mixed
     */
    public function getListFileDoc($manage_work_id){
        return $this
            ->select(
                $this->table.'.manage_document_file_id',
                $this->table.'.file_name',
                $this->table.'.path'
            )
            ->where($this->table.'.manage_work_id',$manage_work_id)
            ->orderBy($this->table.'.created_at','DESC')
            ->get();
    }

    /**
     * Xoá các file theo hồ sơ
     * @param $manage_document_id
     */
    public function deleteFileByWork($manage_document_file_id){
        return $this
            ->where('manage_document_file_id',$manage_document_file_id)
            ->delete();
    }

    /**
     * Tạo file hồ sơ
     * @param $data
     */
    public function createFileByDocument($data){
        return $this->insertGetId($data);
    }

    /**
     * lấy chi tiết file
     * @param $manage_document_file_id
     * @return mixed
     */
    public function getDetailFile($manage_document_file_id){
        return $this
            ->select(
                $this->table.'.manage_document_file_id',
                $this->table.'.file_name',
                $this->table.'.path'
            )
            ->where('manage_document_file_id',$manage_document_file_id)
            ->first();
    }

    /**
     * lấy chi tiết file noti
     * @param $manage_document_file_id
     * @return mixed
     */
    public function getDetailFileNoti($manage_document_file_id){
        return $this
            ->select(
                $this->table.'.manage_document_file_id',
                $this->table.'.file_name',
                $this->table.'.path',
                $this->table.'.created_by',
                'manage_work.manage_work_id',
                'manage_work.manage_work_title',
                'staffs.full_name as staff_name'
            )
            ->join('manage_work','manage_work.manage_work_id',$this->table.'.manage_work_id')
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where('manage_document_file_id',$manage_document_file_id)
            ->first();
    }
}