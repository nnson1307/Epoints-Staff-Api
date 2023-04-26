<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectTagTable extends Model
{
protected $table = "manage_project_tag";
protected $primaryKey = "manage_tag_id";


    public function createdTag($dataTag){
            return $this
                ->insertGetId($dataTag);
    }
    public function deleteOldTag($input){
        return $this
            ->where("manage_project_tag.manage_project_id",$input)
            ->delete();
    }
    public function getTagProject($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.tag_id as manage_tag_id",
                "manage_tags.manage_tag_name"
            )
            ->where("{$this->table}.is_active" , 1)
        ->leftJoin("manage_tags","{$this->table}.tag_id","manage_tags.manage_tag_id");

        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject']!= null ){
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id",$filter['arrIdProject']);
        }
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id']!= null ){
            $mSelect = $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }

        return $mSelect->get()->toArray();
    }






}