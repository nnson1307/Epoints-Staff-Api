<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/06/2021
 * Time: 17:10
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class WarrantyCardTable extends Model
{
    protected $table = "warranty_card";
    protected $primaryKey = "warranty_card_id";
    protected $fillable = [
        'warranty_card_id',
        'warranty_card_code',
        'customer_code',
        'warranty_packed_code',
        'date_actived',
        'date_expired',
        'quota',
        'warranty_percent',
        'warranty_value',
        'description',
        'object_type',
        'object_type_id',
        'object_code',
        'object_price',
        'object_serial',
        'object_note',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'order_code',
    ];

    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    public function edit($data, $warrantyCardId)
    {
        return $this->where('warranty_card_id', $warrantyCardId)->update($data);
    }

    /**
     * Huỷ thẻ theo mã đơn hàng
     *
     * @param $orderCode
     * @return mixed
     */
    public function cancelWarrantyCardByOrderCode($orderCode)
    {
        return $this->where('order_code', $orderCode)->update(['status' => 'cancel']);
    }
}