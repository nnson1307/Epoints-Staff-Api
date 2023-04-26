<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageDocumentFileTable extends Model
{
    protected $table = "manage_document_file";
    protected $primaryKey = "manage_document_file_id";

    public function getFile($manage_work_id){
        $oSelect = $this
            ->select(
                "{$this->table}.file_name",
                "{$this->table}.file_type"
            )
            ->where("{$this->table}.manage_work_id",$manage_work_id);
        return $oSelect->get();

    }
}