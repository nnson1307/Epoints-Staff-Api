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

class TicketAcceptanceIncurredTable extends Model
{
    protected $table = "ticket_acceptance_incurred";
    protected $primaryKey = "ticket_acceptance_incurred_id";

//    Lấy danh sách file theo loại
    public function getAcceptanceIncurred($ticket_acceptance_id){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_acceptance_incurred_id',
                $this->table.'.product_id',
                $this->table.'.quantity',
                $this->table.'.money',
                $this->table.'.status',
                $this->table.'.unit_name',
                'ticket_acceptance_incurred.product_name',
                'ticket_acceptance_incurred.product_code'
//                'units.name as unit_name'
            )
            ->leftjoin('products','products.product_id',$this->table.'.product_id')
            ->leftjoin('product_childs','product_childs.product_id','products.product_id')
//            ->leftjoin('units','units.unit_id','product_childs.unit_id')
            ->where($this->table.'.ticket_acceptance_id' , $ticket_acceptance_id)
            ->get();
        return $oSelect;
    }

//    Lấy danh sách file theo loại
    public function getAcceptanceIncurredByTicket($ticket_id){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_acceptance_incurred_id',
                $this->table.'.product_id',
                $this->table.'.quantity',
                $this->table.'.money',
                $this->table.'.status',
                $this->table.'.unit_name',
                'ticket_acceptance_incurred.product_name',
                'ticket_acceptance_incurred.product_code'
//                'units.name as unit_name'
            )
            ->leftjoin('ticket_acceptance','ticket_acceptance.ticket_acceptance_id',$this->table.'.ticket_acceptance_id')
            ->leftjoin('products','products.product_id',$this->table.'.product_id')
            ->leftjoin('product_childs','product_childs.product_id','products.product_id')
//            ->leftjoin('units','units.unit_id','product_childs.unit_id')
            ->where('ticket_acceptance.ticket_id' , $ticket_id)
            ->get();
        return $oSelect;
    }

//    Tạo vật tư phát sinh
    public function createdIncurred($data){
        return $this->insert($data);
    }

//    Xoá vật tư
    public function deleteRequestFormDetail($arrDetailId,$acceptanceId){
        return $this
            ->where('ticket_acceptance_id',$acceptanceId)
            ->whereNotIn('ticket_acceptance_incurred_id',$arrDetailId)
            ->delete();
    }

//    Cập nhật
    public function updateRequestFormDetail($data,$idDetail){
        return $this
            ->where('ticket_acceptance_incurred_id',$idDetail)
            ->update($data);
    }
}