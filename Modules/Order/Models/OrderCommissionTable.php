<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 15:51
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class OrderCommissionTable extends Model
{
    protected $table = "order_commission";
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'order_detail_id',
        'refer_id',
        'staff_id',
        'refer_money',
        'staff_money',
        'status',
        'staff_commission_rate',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'note'
    ];

    /**
     * Thêm hoa hồng của đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }
}