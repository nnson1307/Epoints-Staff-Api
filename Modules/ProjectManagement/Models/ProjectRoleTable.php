<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectRoleTable extends Model
{
protected $table = "manage_project_role";
protected $primaryKey = "manage_project_role_id";


    public function getRole(){

        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_role_id",
                "{$this->table}.manage_project_role_code",
                "{$this->table}.manage_project_role_name",
                "{$this->table}.is_active",
                "{$this->table}.created_at",
                "{$this->table}.created_by"

            );
        return $mSelect->get()->toArray();
    }
}