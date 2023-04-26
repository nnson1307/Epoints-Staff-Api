<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class TagTable extends Model
{
    protected $table = "cpo_tag";
    protected $primaryKey = "cpo_tag_id";

    const NOT_DELETED = 0;

    public function getDataTag()
    {
        return $this
            ->select(
                "tag_id",
                "keyword",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
    public function addTag($data){
        return $this->insertGetId($data);
    }
    public function getTagName($tagId){
        return $this
            ->select(
                "tag_id",
                "keyword",
                "name as tag_name"
            )
            ->whereIn("{$this->table}.tag_id",$tagId)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
    public function getInfoTag($dataTag = []){
        $oSelect = $this
            ->select(
                "{$this->table}.tag_id",
                "{$this->table}.type",
                "{$this->table}.keyword",
                "{$this->table}.name"
            )
            ->where("is_deleted", self::NOT_DELETED)
           -> whereIn("{$this->table}.tag_id",$dataTag);

        return $oSelect->get()->toArray();
    }
}