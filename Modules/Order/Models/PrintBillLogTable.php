<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PrintBillLogTable extends Model
{
    protected $table = 'print_log';
    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];


    //Kiểm tra đơn hàng được in
    public function checkPrintBillOrder($orderId)
    {
        $select = $this->select(
            'branch_id', 'order_code',
            'staff_print_reply_by', 'staff_print_by'
        )->where('order_code', $orderId)->get();
        return $select;
    }

    //get biggest id
    public function getBiggestId()
    {
        $select = $this->select('id')->whereRaw('id = (select max(`id`) from print_log)')->first();
        return $select;
    }
}