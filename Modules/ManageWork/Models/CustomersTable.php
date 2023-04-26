<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomersTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    /**
     * lấy danh sách khách hàng
     */
    public function getListCustomer($data){
        $oSelect = $this
            ->select(
                'customer_id',
                'full_name as customer_name',
                'customer_avatar'
            )
            ->where('is_actived',1)
            ->where('is_deleted',0);

        if (isset($data['customer_name'])){
            $oSelect = $oSelect->where('full_name','like','%'.$data['customer_name'].'%');
        }

        return $oSelect->orderBy('created_at','DESC')->get();
    }

    public function getDetail($customerId){
        return $this->where('customer_id',$customerId)->first();
    }
}