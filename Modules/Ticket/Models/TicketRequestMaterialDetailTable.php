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

class TicketRequestMaterialDetailTable extends Model
{
    protected $table = "ticket_request_material_detail";
    protected $primaryKey = "ticket_request_material_detail_id";

//    Tạo vật tư
    public function createdRequestFormDetail($data){
        return $this->insert($data);
    }

//    Chỉnh sửa vật tư
    public function updateRequestFormDetail($data,$ticketRequestMaterialDetailId){
        return $this->where('ticket_request_material_detail_id',$ticketRequestMaterialDetailId)->update($data);
    }

//    Xoá vật tư
    public function deleteRequestFormDetail($arrDetailId,$materialId){
        return $this
            ->where('ticket_request_material_id',$materialId)
            ->whereNotIn('ticket_request_material_detail_id',$arrDetailId)
            ->delete();
    }

//    Danh sách vật tư
    public function listRequestMaterialDetail($arrIdRequestForm){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_request_material_detail_id',
                $this->table.'.product_id',
                $this->table.'.quantity',
                $this->table.'.quantity_approve',
                $this->table.'.quantity_return',
                $this->table.'.quantity_reality',
                $this->table.'.status',
                'products.product_name',
                'product_childs.product_code',
                'units.name as unit_name'
            )
            ->join('products','products.product_id',$this->table.'.product_id')
            ->join('product_childs','product_childs.product_id','products.product_id')
            ->join('units','units.unit_id','product_childs.unit_id')
            ->whereIn('ticket_request_material_id',$arrIdRequestForm)
            ->get();
        return $oSelect;
    }

//    Thông tin vật tư
    public function listRequestMaterialDetailList($arrIdRequestForm){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_request_material_detail_id',
                $this->table.'.product_id',
                DB::raw("SUM(ticket_request_material_detail.quantity) as quantity"),
                DB::raw("SUM(ticket_request_material_detail.quantity_approve) as quantity_approve"),
                DB::raw("SUM(ticket_request_material_detail.quantity_return) as quantity_return"),
                DB::raw("SUM(ticket_request_material_detail.quantity_reality) as quantity_reality"),
//                $this->table.'.quantity_approve',
//                $this->table.'.quantity_return',
//                $this->table.'.quantity_reality',
                $this->table.'.status',
                'products.product_name',
                'product_childs.product_code',
                'units.name as unit_name'
            )
            ->join('products','products.product_id',$this->table.'.product_id')
            ->join('product_childs','product_childs.product_id','products.product_id')
            ->join('units','units.unit_id','product_childs.unit_id')
            ->whereIn('ticket_request_material_id',$arrIdRequestForm)
            ->groupBy($this->table.'.product_id')
            ->get();

        foreach ($oSelect as $key => $item){
            $oSelect[$key]['quantity'] = (int)$oSelect[$key]['quantity'];
            $oSelect[$key]['quantity_approve'] = (int)$oSelect[$key]['quantity_approve'];
            $oSelect[$key]['quantity_return'] = (int)$oSelect[$key]['quantity_return'];
            $oSelect[$key]['quantity_reality'] = (int)$oSelect[$key]['quantity_reality'];
        }

        return $oSelect;
    }

//    Danh sách vật tư theo ticket_id
    public function listRequestMaterialDetailByTicketId($ticketId){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_request_material_detail_id',
                $this->table.'.product_id',
                $this->table.'.quantity',
                $this->table.'.quantity_approve',
                $this->table.'.quantity_return',
                $this->table.'.quantity_reality',
                $this->table.'.status',
                'products.product_name',
                'product_childs.product_code',
                'units.name as unit_name'
            )
            ->join('ticket_request_material','ticket_request_material.ticket_request_material_id',$this->table.'.ticket_request_material_id')
            ->join('products','products.product_id',$this->table.'.product_id')
            ->join('product_childs','product_childs.product_id','products.product_id')
            ->join('units','units.unit_id','product_childs.unit_id')
            ->where('ticket_request_material.ticket_id',$ticketId)
            ->get();
        return $oSelect;
    }

    /**
     * Danh sách vật tư cho biên bản nghiệm thu
     * @param $ticketId
     * @return mixed
     */
    public function listRequestMaterialAcceptanceDetailByTicketId($ticketId){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_request_material_detail_id',
                $this->table.'.product_id',
                $this->table.'.quantity',
                $this->table.'.quantity_approve',
                $this->table.'.quantity_return',
                $this->table.'.quantity_reality',
                $this->table.'.status',
                'products.product_name',
                'product_childs.product_code',
                'units.name as unit_name'
            )
            ->join('ticket_request_material','ticket_request_material.ticket_request_material_id',$this->table.'.ticket_request_material_id')
            ->join('products','products.product_id',$this->table.'.product_id')
            ->join('product_childs','product_childs.product_id','products.product_id')
            ->join('units','units.unit_id','product_childs.unit_id')
            ->where('ticket_request_material.ticket_id',$ticketId)
            ->where($this->table.'.quantity_approve','<>',0)
            ->get();
        return $oSelect;
    }

    public function getDetail($ticketRequestMaterialDetailId){
        return $this
            ->where('ticket_request_material_detail_id',$ticketRequestMaterialDetailId)
            ->first();
    }

    public function deleteForm($ticketRequestMaterialId){
        return $this->where('ticket_request_material_id',$ticketRequestMaterialId)->delete();
    }

}