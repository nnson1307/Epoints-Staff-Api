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

class TicketIssueTable extends Model
{
    protected $table = "ticket_issue";
    protected $primaryKey = "ticket_issue_id";

//    Lấy danh sách file theo loại
    public function getListIssue($data){
        $oSelect = $this
            ->select(
                'ticket_issue_id',
                'name',
                'level'
            )
            ->where($this->table.'.is_active',1);

        if (isset($data['ticket_issue_group_id'])){
            $oSelect = $oSelect->where('ticket_issue_group_id',$data['ticket_issue_group_id']);
        }

        if (isset($data['name'])){
            $oSelect = $oSelect->where('name','%'.$data['name'].'%');
        }
        return $oSelect->orderBy($this->table.'.ticket_issue_id','DESC')->get();
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}