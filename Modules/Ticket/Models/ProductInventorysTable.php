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

class ProductInventorysTable extends Model
{
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";

//    Tổng tồn kho vật tư
    public function getCountInventory($productId){
        $oSelect = $this
            ->select(
                DB::raw('SUM(product_inventorys.quantity) as total_quantity')
            )
            ->where($this->table.'.product_id',$productId)
            ->first();
        return $oSelect['total_quantity'] == null ? 0 : $oSelect['total_quantity'];
    }

}