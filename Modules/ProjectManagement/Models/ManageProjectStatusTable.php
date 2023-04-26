<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class ManageProjectStatusTable extends Model
{
protected $table = "manage_project_status";
protected $primaryKey = "manage_project_status_id";

    public  function getStatus(){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_status_id",
                "{$this->table}.manage_project_status_name",
                "{$this->table}.manage_project_status_color"
            );
        return $mSelect->get()->toArray();
    }

    public function getDetail($manage_project_status_id){
        return $this
            ->where('manage_project_status_id',$manage_project_status_id)
            ->first();
    }
}