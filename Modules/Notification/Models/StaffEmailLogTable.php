<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/04/2021
 * Time: 10:28
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class StaffEmailLogTable extends Model
{
    protected $table = "staff_email_log";
    protected $primaryKey = "staff_email_log_id";

    /**
     * Thêm email log
     */
    public function addEmail($data){
        return $this->insert($data);
    }
}