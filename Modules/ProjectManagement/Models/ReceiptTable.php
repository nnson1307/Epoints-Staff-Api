<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ReceiptTable extends Model
{
protected $table = "receipts";
protected $primaryKey = "receipt_id";
protected $casts  = [
    'total_money' => 'double'
];

    public function getListReceipt($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.staff_id",
                "staffs.full_name",
                "{$this->table}.total_money",
                "{$this->table}.status",
                "{$this->table}.created_at"
            )
            ->leftJoin("staffs", "{$this->table}.staff_id","staffs.staff_id");
        if(isset($filter['arrIdReceipt']) && $filter['arrIdReceipt'] != null && $filter['arrIdReceipt'] != []){
            $mSelect->whereIn("{$this->table}.receipt_id",$filter['arrIdReceipt']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_create = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        return $mSelect->get()->toArray();
    }
}