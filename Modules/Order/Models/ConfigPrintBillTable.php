<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ConfigPrintBillTable extends Model
{
    use ListTableTrait;
    protected $table = "config_print_bill";
    protected $primaryKey = "id";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    public function getItem($id)
    {
        return $this
            ->select(
                'printed_sheet',
                'is_print_reply',
                'print_time',
                'is_show_logo',
                'is_show_unit',
                'is_show_address',
                'is_show_phone',
                'is_show_order_code',
                'is_show_cashier',
                'is_show_customer',
                'is_show_datetime',
                'is_show_footer',
                'template',
                'symbol',
                'is_total_bill',
                'is_total_discount',
                'is_total_amount',
                'is_total_receipt',
                'is_amount_return',
                'note_footer'
            )
            ->where($this->primaryKey, $id)
            ->first();
    }

}