<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class TagTable extends Model
{
protected $table = "manage_tags";
protected $primaryKey = "manage_tag_id";

public  function getTag(){
    $mSelect = $this
        ->select(
            "{$this->table}.manage_tag_id",
            "{$this->table}.manage_tag_name"
        );
    return $mSelect->get()->toArray();
}
    public function createdTag($dataTag){
        return $this
            ->insertGetId($dataTag);
    }

}