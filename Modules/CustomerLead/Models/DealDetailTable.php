<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class DealDetailTable extends Model
{
    protected $table = "cpo_deal_details";
    protected $primaryKey = "deal_detail_id";

    protected $casts = [
        "price" => 'float',
        "discount" => 'float',
        "quantity" => 'integer',
        "amount" => 'float'
    ];

    public function createdDetailDeal($dataDetail){
        return $this
            ->insertGetId($dataDetail);
    }
    public function updateDetailDeal($dataUpdateDetail,$dealCode){

        return $this
            ->insertGetId($dataUpdateDetail);
    }
    //xoa DEAL
    public function deleteDetail($input){
        return $this
            ->where($this->table.'.deal_code',$input)
            ->delete();
    }

    /**
     * Lấy sp, dv, thẻ dv của deal
     *
     * @param $dealCode
     * @return mixed
     */
    public function getDetailByDeal($dealCode)
    {
        return $this
            ->select(
                "deal_detail_id",
                "object_id",
                "object_name",
                "object_type",
                "price",
                "quantity",
                "discount",
                "amount"
            )
            ->where("deal_code", $dealCode)
            ->get();
    }
}