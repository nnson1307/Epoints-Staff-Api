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

class ManageTagsTable extends Model
{
    protected $table = "manage_tags";
    protected $primaryKey = "manage_tag_id";

    /**
     * Lấy danh sách tags
     */
    public function getListTags($data){
        $oSelect = $this
            ->select(
                'manage_tag_id',
                'manage_tag_name',
                'manage_tag_icon'
            )
            ->where('is_active',1);

        if (isset($data['manage_tag_name'])){
            $oSelect = $oSelect->where('manage_tag_name','like','%'.$data['manage_tag_name'].'%');
        }

        if (isset($data['manage_tag_id'])){
            $oSelect = $oSelect->where('manage_tag_id',$data['manage_tag_id']);
        }

        return $oSelect->orderBy('created_at','DESC')->get();
    }

    /**
     * Tạo tag mới
     * @param $data
     */
    public function addTag($data){
        return $this->insertGetId($data);
    }

    /**
     * lấy chi tiết tag
     * @param $data
     * @return mixed
     */
    public function getTags($manage_tag_id){
        $oSelect = $this
            ->select(
                'manage_tag_id',
                'manage_tag_name',
                'manage_tag_icon'
            )
            ->where('is_active',1)
            ->where('manage_tag_id',$manage_tag_id);

        return $oSelect->first();
    }
}