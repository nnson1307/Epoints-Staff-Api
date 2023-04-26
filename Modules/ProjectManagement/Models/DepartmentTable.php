<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class DepartmentTable extends Model
{
protected $table = "departments";
protected $primaryKey = "department_id";

public  function getDepartment(){
    $mSelect = $this
        ->select(
            "{$this->table}.department_id",
            "{$this->table}.department_name",
            "{$this->table}.slug"
        );
    return $mSelect->get()->toArray();
}
}