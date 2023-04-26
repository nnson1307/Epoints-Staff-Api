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

class TicketFileTable extends Model
{
    protected $table = "ticket_file";
    protected $primaryKey = "ticket_file_id";

    protected $fillable = ['ticket_file_id', 'ticket_id','type', 'path','group','created_by','created_at', 'note'];
//    Lấy danh sách file theo loại
    public function getListFile($ticket_id,$group = null){
        $oSelect = $this;
        $oSelect = $oSelect->select(
            'path',
            'type'
        );

        if ($group != null ){
            $oSelect =  $oSelect->where($this->table.'.group',$group);
        }
        $oSelect = $oSelect
            ->where($this->table.'.ticket_id',$ticket_id)
            ->orderBy($this->table.'.ticket_file_id','DESC')
            ->get();
        return $oSelect;
    }

//    Tạo file biên bản nghiệm thu
    public function createFile($data){
        return $this->insert($data);
    }

    public function deleteFile($ticket_id,$group){
        return $this
            ->where('ticket_id',$ticket_id)
            ->where('group',$group)
            ->delete();
    }

    public function add(array $data)
    {
        $oTicketFile = $this->create($data);
        return $oTicketFile->ticket_file_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

}