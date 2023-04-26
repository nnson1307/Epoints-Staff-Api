<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Ticket\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketQueueTable extends Model
{
    protected $table = 'ticket_queue';
    protected $primaryKey = 'ticket_queue_id';

    protected $fillable = ['ticket_queue_id', 'queue_name','department_id','email', 'description', 'created_by',
    'updated_by', 'created_at', 'updated_at','is_actived'];

    public function getName(){
        $oSelect= self::select("ticket_queue_id","queue_name")->where("is_actived", 1)->orderBy('queue_name', 'ASC')->get();
        return $oSelect;
    }

}