<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PrintBillDeviceTable extends Model
{
    protected $table = 'print_bill_devices';
    protected $primaryKey = 'print_bill_device_id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const IS_REPRESENTATIVE = 1;

    public function getPrinters($branch_id)
    {
        return $this->select(
                        "{$this->table}.print_bill_device_id",
                        "{$this->table}.branch_id",
                        "b.branch_name",
                        "{$this->table}.printer_name",
                        "{$this->table}.printer_ip",
                        "{$this->table}.printer_port",
                        "{$this->table}.template",
                        "{$this->table}.template_width",
                        "{$this->table}.is_default"
                    )
                    ->leftJoin("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
                    ->where("{$this->table}.branch_id", $branch_id)
                    ->where("{$this->table}.is_actived", 1)
                    ->where("{$this->table}.is_deleted", 0)
                    ->get();
    }

}