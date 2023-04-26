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

class TicketIssueGroupTable extends Model
{
    protected $table = "ticket_issue_group";
    protected $primaryKey = "ticket_issue_group_id";

//    Lấy danh sách file theo loại
    public function getListIssueGroup($data){
        $oSelect = $this
            ->select(
                'ticket_issue_group_id',
                'type',
                'name',
            )
            ->where($this->table.'.is_active',1);

        if (isset($data['name'])){
            $oSelect = $oSelect->where('name','%'.$data['name'].'%');
        }

        return $oSelect->get();
    }
}