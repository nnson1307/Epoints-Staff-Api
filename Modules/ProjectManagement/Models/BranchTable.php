<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BranchTable extends Model
{
protected $table = "branches";
protected $primaryKey = "branch_id";


    public function getBranch(){

        $mSelect = $this
            ->select(
                "{$this->table}.branch_id",
                "{$this->table}.branch_name",
                "{$this->table}.slug",
                "{$this->table}.address",
                "{$this->table}.phone",
                "{$this->table}.email"

            );
        return $mSelect->get();
    }
}